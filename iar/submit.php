<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'submit_errors.log');

header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "dbpsa");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// 1. Get and validate single fields
$requiredSingleFields = [
    'supplier', 'pr_no', 'iar_no', 'date', 'invoice_no',
    'responsibility_center', 'fund_cluster', 'date_inspected',
    'final_date_received', 'i_officer', 'custodian'
];

$postData = [];
foreach ($requiredSingleFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
    $postData[$field] = $_POST[$field];
}

// 2. Get and normalize array fields - CRITICAL FIX
$arrayFields = [
    'stock_code' => 'stock_code',
    'item' => 'item',
    'dscrtn' => 'descd',  // Note: Your HTML uses 'dscrtn[]' but database expects 'descd'
    'unit' => 'unit',
    'quantity' => 'quantity',
    'cost' => 'cost'
];

$itemData = [];
$entryCount = null;

foreach ($arrayFields as $formField => $dbField) {
    // Handle both array and non-array inputs safely
    if (!isset($_POST[$formField])) {
        $itemData[$dbField] = [];
    } else {
        $itemData[$dbField] = is_array($_POST[$formField]) ? $_POST[$formField] : [$_POST[$formField]];
    }
    
    // Remove empty values but preserve array structure
    $itemData[$dbField] = array_values(array_filter($itemData[$dbField], function($value) {
        return $value !== '' && $value !== null;
    }));

    // Set initial count or verify consistency
    if ($entryCount === null) {
        $entryCount = count($itemData[$dbField]);
    } elseif (count($itemData[$dbField]) !== $entryCount) {
        error_log("ARRAY MISMATCH:\n" . print_r([
            'field' => $formField,
            'expected' => $entryCount,
            'actual' => count($itemData[$dbField]),
            'values' => $itemData[$dbField]
        ], true));
        
        echo json_encode([
            'success' => false,
            'message' => "Number of items doesn't match for field: $formField",
            'details' => [
                'expected_count' => $entryCount,
                'actual_count' => count($itemData[$dbField])
            ]
        ]);
        exit;
    }
}

// 3. Process items with transaction
$conn->begin_transaction();
try {
    for ($i = 0; $i < $entryCount; $i++) {
        // Skip if essential fields are empty
        if (empty($itemData['stock_code'][$i]) || empty($itemData['item'][$i])) {
            continue;
        }

        // Prepare data with proper escaping and defaults
        $data = [
            'stock_code' => $conn->real_escape_string($itemData['stock_code'][$i] ?? ''),
            'item' => $conn->real_escape_string($itemData['item'][$i] ?? ''),
            'descd' => $conn->real_escape_string($itemData['descd'][$i] ?? ''),
            'unit' => $conn->real_escape_string($itemData['unit'][$i] ?? 'pc'),
            'quantity' => (int)($itemData['quantity'][$i] ?? 0),
            'cost' => (float)($itemData['cost'][$i] ?? 0)
        ];

        // Insert into tbl_iar
        $sql_iar = "INSERT INTO tbl_iar (
            supplier, pr_no, iar_no, date, property_no, descd, item, unit, quantity,
            invoice_no, rcc, date_inspected, date_recieved, i_officer, custodian, cost
        ) VALUES (
            '{$postData['supplier']}', '{$postData['pr_no']}', '{$postData['iar_no']}', '{$postData['date']}',
            '{$data['stock_code']}', '{$data['descd']}', '{$data['item']}', '{$data['unit']}', {$data['quantity']},
            '{$postData['invoice_no']}', '{$postData['responsibility_center']}',
            '{$postData['date_inspected']}', '{$postData['final_date_received']}',
            '{$postData['i_officer']}', '{$postData['custodian']}', {$data['cost']}
        )";

        if (!$conn->query($sql_iar)) {
            throw new Exception("tbl_iar insert failed: " . $conn->error);
        }

        // Insert into tbl_sc
        $sql_sc = "INSERT INTO tbl_sc (
            stock_no, item, dscrtn, unit, date, receipt_qty, fund, entity
        ) VALUES (
            '{$data['stock_code']}', '{$data['item']}', '{$data['descd']}', '{$data['unit']}',
            '{$postData['date']}', {$data['quantity']}, '{$postData['fund_cluster']}',
            'Philippine Statistics Authority'
        )";

        if (!$conn->query($sql_sc)) {
            throw new Exception("tbl_sc insert failed: " . $conn->error);
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'IAR successfully submitted!']);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
    ob_end_flush();
}