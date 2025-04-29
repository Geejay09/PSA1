<?php

error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show warnings on page
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Log them to a file

$stock_code = $_GET['stock_code'] ?? '';

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed");
}

$response = [];

$stmt = $conn->prepare("SELECT item, descode FROM tbl_items WHERE stock_code = ?");
$stmt->bind_param("s", $stock_code);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $response = [
        'item' => $row['item'],
        'description' => $row['descode']
    ];
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>