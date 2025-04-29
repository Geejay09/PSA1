<?php
// Start output buffering to prevent early output
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'submit_errors.log');

header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get POST data safely
$supplier = $_POST['supplier'] ?? '';
$iar_no = $_POST['iar_no'] ?? '';
$date = $_POST['date'] ?? '';
$pr_no = $_POST['pr_no'] ?? '';
$invoice_no = $_POST['invoice_no'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$responsibility_center = $_POST['responsibility_center'] ?? '';
$fund_cluster = $_POST['fund_cluster'] ?? '';
$date_inspected = $_POST['date_inspected'] ?? '';
$date_received = $_POST['final_date_received'] ?? '';
$i_officer = $_POST['i_officer'] ?? '';
$custodian = $_POST['custodian'] ?? '';

// Get item arrays safely
$stock_codes = $_POST['stock_code'] ?? [];
$descriptions = $_POST['dscrtn'] ?? [];
$items = $_POST['item'] ?? [];
$units = $_POST['unit'] ?? [];
$quantities = $_POST['quantity'] ?? [];

$allSuccess = true;

// Ensure all arrays have the same length
$entryCount = count($stock_codes);
if (
    $entryCount !== count($descriptions) ||
    $entryCount !== count($items) ||
    $entryCount !== count($units) ||
    $entryCount !== count($quantities)  
) {
    echo json_encode(['success' => false, 'message' => 'Input arrays are not aligned.']);
    exit;
}

for ($i = 0; $i < $entryCount; $i++) {
    // Skip if any essential field is empty
    if (
        empty($stock_codes[$i]) ||
        empty($descriptions[$i]) ||
        empty($items[$i]) ||
        empty($units[$i]) ||
        empty($quantities[$i])
    ) {
        continue; // Optionally skip or break depending on your use case
    }

    $stock_code = $conn->real_escape_string($stock_codes[$i]);
    $descd = $conn->real_escape_string($descriptions[$i]);
    $item = $conn->real_escape_string($items[$i]);
    $unit = $conn->real_escape_string($units[$i]);
    $qty = intval($quantities[$i]);

    // Insert into tbl_iar
    $sql_iar = "INSERT INTO tbl_iar (
        supplier, iar_no, date, pr_no, invoice_no, d, rcc, property_no, descd, item, unit, quantity, date_inspected, 
        date_recieved, i_officer, custodian
    ) VALUES (
        '$supplier', '$iar_no', '$date', '$pr_no', '$invoice_no', '$invoice_date', '$responsibility_center',
        '$stock_code', '$dscrtn', '$item', '$unit', '$qty', '$date_inspected', '$date_received', '$i_officer', '$custodian'
    )";

    if (!$conn->query($sql_iar)) {
        $allSuccess = false;
        $errorMsg = $conn->error;
        break;
    }

    // Insert into tbl_sc
    $sql_sc = "INSERT INTO tbl_sc (
        stock_no, item, dscrtn, unit, date, receipt_qty, fund, entity
    ) VALUES (
        '$stock_code', '$item', '$descd', '$unit', '$date', '$qty', '$fund_cluster', 'Philippine Statistics Authority'
    )";

    if (!$conn->query($sql_sc)) {
        $allSuccess = false;
        $errorMsg = $conn->error;
        break;
    }
}

$conn->close();
ob_end_clean(); // Clean buffer so output is pure JSON

if ($allSuccess) {
    echo json_encode(['success' => true, 'message' => 'IAR successfully submitted!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error saving data: ' . $errorMsg]);
}
