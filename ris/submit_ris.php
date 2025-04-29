<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Fetch POST data
$stock_nos = $_POST['stock_no'] ?? [];
$items = $_POST['item'] ?? [];
$descriptions = $_POST['dscrtn'] ?? [];
$units = $_POST['unit'] ?? [];
$quantities = $_POST['qty'] ?? [];
$issued_quantities = $_POST['i_qty'] ?? [];
$remarks = $_POST['remarks'] ?? [];
$division = $_POST['division'] ?? '';
$office = $_POST['office'] ?? '';
$rcc = $_POST['rcc'] ?? '';
$ris_no = $_POST['ris_no'] ?? '';
$purpose = $_POST['purpose'] ?? '';
$receiver = $_POST['receiver'] ?? '';
$fc = $_POST['fc'] ?? '';

// Loop through the rows
for ($i = 0; $i < count($stock_nos); $i++) {
    $stock_no = $stock_nos[$i] ?? '';
    $item = $items[$i] ?? '';
    $description = $descriptions[$i] ?? '';
    $unit = $units[$i] ?? '';
    $qty = $quantities[$i] ?? 0;
    $i_qty = $issued_quantities[$i] ?? 0;
    $remark = $remarks[$i] ?? '';

    // Insert into RIS table
    $stmt = $conn->prepare("INSERT INTO tbl_ris (division, office, rcc, ris_no, stock_no, item, des, unit, qty, i_qty, remarks, purpose, receiver, fc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssiiisss", $division, $office, $rcc, $ris_no, $stock_no, $item, $description, $unit, $qty, $i_qty, $remark, $purpose, $receiver, $fc);
    $stmt->execute();
    $stmt->close();

    // Also insert into Stock Card
    $entity = "Philippine Statistics Authority";
    $date = date('Y-m-d');
    $ref = $ris_no;

    $stmt2 = $conn->prepare("INSERT INTO tbl_sc (item, dscrtn, unit, stock_no, fund, date, ref, receipt_qty, issue_qty, balance_qty, no_days, entity, office) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $balance_qty = 0; // You can update this with actual logic if needed
    $no_days = 0;
    $stmt2->bind_param("ssssssiiisiss", $item, $description, $unit, $stock_no, $fc, $date, $ref, $qty, $i_qty, $balance_qty, $no_days, $entity, $receiver);
    $stmt2->execute();
    $stmt2->close();
}

$conn->close();

echo json_encode(['success' => true, 'message' => 'RIS successfully submitted.']);
?>
