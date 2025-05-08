<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}


header('Content-Type: application/json');

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $query = "
        SELECT ris_no, rcc, stock_no, item, des, unit, qty
        FROM tbl_ris
        WHERE DATE(created_at) = CURDATE()
        ORDER BY ris_no, stock_no
    ";

    $result = $conn->query($query);
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>