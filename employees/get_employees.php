<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

// Check if single employee is requested by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    $sql = "SELECT id, first_name, last_name, email, position, access_level FROM tbl_employees WHERE id = $id AND is_deleted = 0 LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo json_encode($employee);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Employee not found']);
    }

    $conn->close();
    exit;
}

// Otherwise, return full list (with optional search)
$search = $_GET['search'] ?? '';
$where = "WHERE is_deleted = 0";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $where .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%')";
}

$sql = "SELECT id, first_name, last_name, email, position, access_level FROM tbl_employees $where ORDER BY id DESC";
$result = $conn->query($sql);

if ($result) {
    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $employees]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query failed']);
}

$conn->close();
?>
