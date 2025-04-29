<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$stock_no = $_GET['stock_no'] ?? '';

if (empty($stock_no)) {
    http_response_code(400);
    echo json_encode(["error" => "No stock number provided"]);
    exit;
}

// Fetch stock card entries
$sql = "SELECT * FROM tbl_sc WHERE stock_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $stock_no);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Fetch item info for the stock_no
$itemInfo = [];
$sqlItem = "SELECT tbl_items.item, tbl_items.descode AS descode, tbl_sc.unit FROM tbl_items INNER JOIN tbl_sc ON tbl_items.stock_code = tbl_sc.stock_no 
WHERE tbl_items.stock_code = ?";
$stmtItem = $conn->prepare($sqlItem);
$stmtItem->bind_param("s", $stock_no);
$stmtItem->execute();
$itemResult = $stmtItem->get_result();
if ($itemResult->num_rows > 0) {
    $itemInfo = $itemResult->fetch_assoc();
}

$stmt->close();
$stmtItem->close();
$conn->close();

// Return both stock card data and item info in a single JSON response
echo json_encode([
    'rows' => $data,
    'item_info' => $itemInfo
]);
?>
