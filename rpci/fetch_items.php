<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "dbpsa");
$result = $conn->query("SELECT item, descode, stock_code FROM tbl_items WHERE deleted = 0");
$items = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($items);
?>