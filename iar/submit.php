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
    'supplier', 'pr_no', 'iar_no', 'date',
    'responsibility_center', 'fund_cluster', 'date_inspected',
    'final_date_received', 'i_officer', 'custodian'
];

$postData = [];
foreach ($requiredSingleFields as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
    $postData[$field] = $_POST[$field];
}
$postData['invoice_no'] = $_POST['invoice_no'] ?? '';

// 2. Normalize array fields
$arrayFields = [
    'stock_code' => 'stock_code',
    'item' => 'item',
    'dscrtn' => 'descd',
    'unit' => 'unit',
    'quantity' => 'quantity',
    'cost' => 'cost'
];

$itemData = [];
$entryCount = 0;

foreach ($arrayFields as $formField => $dbField) {
    // Get and clean array
    $inputArray = isset($_POST[$formField]) ? (array)$_POST[$formField] : [];
    $cleanedArray = [];

    foreach ($inputArray as $val) {
        $cleanedArray[] = trim($val);
    }

    $itemData[$dbField] = $cleanedArray;

    // Set the maximum entry count based on actual inputs
    $entryCount = max($entryCount, count($cleanedArray));
}

// Pad all arrays to the same length
foreach ($itemData as $key => $arr) {
    while (count($itemData[$key]) < $entryCount) {
        $itemData[$key][] = '';
    }
}

// 3. Process items with transaction
$conn->begin_transaction();
try {
    for ($i = 0; $i < $entryCount; $i++) {
        if (empty($itemData['stock_code'][$i]) || empty($itemData['item'][$i])) {
            continue; // Skip incomplete rows
        }

        // Prepare and sanitize values
        $data = [
            'stock_code' => $conn->real_escape_string($itemData['stock_code'][$i]),
            'item' => $conn->real_escape_string($itemData['item'][$i]),
            'descd' => $conn->real_escape_string($itemData['descd'][$i]),
            'unit' => $conn->real_escape_string($itemData['unit'][$i] ?: 'pc'),
            'quantity' => (int)($itemData['quantity'][$i] ?: 0),
            'cost' => (float)($itemData['cost'][$i] ?: 0)
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
            stock_no, item, dscrtn, unit, date, receipt_qty, fund, entity, ref
        ) VALUES (
            '{$data['stock_code']}', '{$data['item']}', '{$data['descd']}', '{$data['unit']}',
            '{$postData['date']}', {$data['quantity']}, '{$postData['fund_cluster']}',
            'Philippine Statistics Authority', '{$postData['iar_no']}'
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
?>
