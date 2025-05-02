<?php
header('Content-Type: application/json');
session_start();

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Check if any admin exists
$check_admin = $conn->query("SELECT id FROM tbl_employees WHERE access_level = 'admin' AND is_deleted = 0 LIMIT 1");

// Only allow this if no admin exists
if ($check_admin->num_rows > 0) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Initial admin already exists']);
    exit;
}

// Proceed with first admin creation
$required = ['first_name', 'last_name', 'email', 'password'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Sanitize input
$first_name = $conn->real_escape_string(trim($_POST['first_name']));
$last_name = $conn->real_escape_string(trim($_POST['last_name']));
$email = strtolower($conn->real_escape_string(trim($_POST['email'])));
$position = "System Administrator";

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// Check password strength
if (strlen($_POST['password']) < 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters']);
    exit;
}

// Hash password
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Insert first admin user
$stmt = $conn->prepare("
    INSERT INTO tbl_employees 
    (first_name, last_name, email, password, position, access_level, is_deleted) 
    VALUES (?, ?, ?, ?, ?, 'admin', 0)
");
$stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $position);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success', 
        'message' => 'First admin account created successfully',
        'user_id' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to create admin account: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>