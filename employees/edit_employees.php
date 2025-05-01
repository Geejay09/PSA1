<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['position'], $_POST['access_level'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $access_level = $_POST['access_level'];

    // If password is provided, hash it and update
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE tbl_employees SET first_name = ?, last_name = ?, email = ?, password = ?, position = ?, access_level = ? WHERE id = ? AND is_deleted = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $password, $position, $access_level, $id);
    } else {
        $sql = "UPDATE tbl_employees SET first_name = ?, last_name = ?, email = ?, position = ?, access_level = ? WHERE id = ? AND is_deleted = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $position, $access_level, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating employee: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
exit;
?>
