<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Enable error reporting for debugging (optional but recommended during development)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

    // Insert into tbl_ris
    $stmt = $conn->prepare("INSERT INTO tbl_ris (division, office, rcc, ris_no, stock_no, item, des, unit, qty, i_qty, remarks, purpose, receiver, fc)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssiiisss", $division, $office, $rcc, $ris_no, $stock_no, $item, $description, $unit, $qty, $i_qty, $remark, $purpose, $receiver, $fc);
    $stmt->execute();
    $stmt->close();

    // Insert into tbl_sc (without qty or no_days)
    $entity = "Philippine Statistics Authority";
    $date = date('Y-m-d');
    $ref = $ris_no;
    $balance_qty = 0; // placeholder

    $stmt2 = $conn->prepare("INSERT INTO tbl_sc (item, dscrtn, unit, stock_no, fund, date, ref, issue_qty, balance_qty, entity, office)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("sssssssiiis", $item, $description, $unit, $stock_no, $fc, $date, $ref, $i_qty, $balance_qty, $entity, $receiver);
    $stmt2->execute();
    $stmt2->close();
}

$conn->close();

echo json_encode(['success' => true, 'message' => 'RIS successfully submitted.']);
?>
