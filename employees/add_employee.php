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

// Verify admin access (but allow if no admin exists yet)
$admin_check = $conn->query("SELECT id FROM tbl_employees WHERE access_level = 'admin' AND is_deleted = 0 LIMIT 1");
if ($admin_check->num_rows > 0 && (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin')) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

// Verify admin access (add this if you want to restrict to admins only)
if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Required fields
$required = ['first_name', 'last_name', 'email', 'password', 'position', 'access_level'];
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
$position = $conn->real_escape_string(trim($_POST['position']));
$access_level = in_array(strtolower($_POST['access_level']), ['admin', 'staff', 'viewer']) 
    ? strtolower($_POST['access_level']) 
    : 'staff';

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

// Start transaction
$conn->begin_transaction();

try {
    // Check if email exists with lock to prevent race conditions
    $check_stmt = $conn->prepare("SELECT id FROM tbl_employees WHERE LOWER(email) = LOWER(?) FOR UPDATE");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        throw new Exception('Email already exists');
    }

    // Hash password
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert new user
    $insert_stmt = $conn->prepare("
        INSERT INTO tbl_employees 
        (first_name, last_name, email, password, position, access_level, is_deleted) 
        VALUES (?, ?, ?, ?, ?, ?, 0)
    ");
    $insert_stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $position, $access_level);
    
    if (!$insert_stmt->execute()) {
        throw new Exception($insert_stmt->error);
    }
    
    $conn->commit();
    
    // Log the action
    error_log("New user created: $email by ".$_SESSION['email']);
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'User created successfully',
        'user_id' => $insert_stmt->insert_id,
        'user' => [
            'name' => "$first_name $last_name",
            'email' => $email,
            'position' => $position,
            'access_level' => $access_level
        ]
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(409);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage(),
        'suggestion' => $e->getMessage() === 'Email already exists' 
            ? 'Please use a different email address' 
            : 'Please try again later'
    ]);
} finally {
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
    $conn->close();
}
?>