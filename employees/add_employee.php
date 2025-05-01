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
    if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['position'], $_POST['access_level'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $position = $_POST['position'];
    $access_level = $_POST['access_level'];

   

    $sql = "INSERT INTO tbl_employees (first_name, last_name, email, password, position, access_level) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $position, $access_level);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Employee added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding employee: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
exit;
?>
