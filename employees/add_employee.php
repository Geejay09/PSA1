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

// Skip admin check if no admin exists (first-time setup)
$adminExists = $conn->query("SELECT 1 FROM tbl_employees WHERE access_level = 'admin' AND is_deleted = 0 LIMIT 1")->num_rows > 0;
if ($adminExists && (!isset($_SESSION['access_level']) || $_SESSION['access_level'] !== 'admin')) {
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
$email = trim($_POST['email']); // Preserve original case
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
    // FIRST: Attempt to insert directly (let database handle uniqueness)
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("
        INSERT INTO tbl_employees 
        (first_name, last_name, email, password, position, access_level, is_deleted) 
        VALUES (?, ?, ?, ?, ?, ?, 0)
    ");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $position, $access_level);
    
    if ($stmt->execute()) {
        $conn->commit();
        echo json_encode([
            'status' => 'success', 
            'message' => 'User created successfully',
            'user_id' => $stmt->insert_id
        ]);
        exit;
    }
    
    // If we get here, insertion failed - check if it's a duplicate error
    if ($conn->errno == 1062) { // MySQL duplicate key error code
        throw new Exception('Email already exists');
    }
    throw new Exception($conn->error);

} catch (Exception $e) {
    $conn->rollback();
    
    // Verify if the email actually exists
    $check = $conn->prepare("SELECT id FROM tbl_employees WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows == 0) {
        // False positive - email doesn't actually exist
        error_log("False duplicate detected for email: $email");
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'System error - please try again',
            'debug' => 'False duplicate detection'
        ]);
    } else {
        // Genuine duplicate
        http_response_code(409);
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage(),
            'suggestion' => 'Please use a different email address'
        ]);
    }
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($check)) $check->close();
    $conn->close();
}
?>