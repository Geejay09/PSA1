<?php
// Start output buffering
ob_start();

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => "Database connection failed"]));
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Only POST requests are allowed']));
}

// Get input data
$input = file_get_contents('php://input');
if (!empty($input)) {
    parse_str($input, $_POST);
}

// Validate email
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Please provide a valid email address']));
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM tbl_employees WHERE email = ? AND is_deleted = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        // For security, don't reveal if email doesn't exist
        die(json_encode([
            'success' => true,
            'message' => 'If this email exists in our system, you will receive a password reset link'
        ]));
    }

    // Generate secure token
    $token = bin2hex(random_bytes(32));
    $tokenHash = password_hash($token, PASSWORD_BCRYPT);
    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiration

    // Store token in database
    $updateStmt = $conn->prepare("UPDATE tbl_employees 
                                SET reset_token = ?, 
                                    reset_token_expires_at = ? 
                                WHERE id = ?");
    $updateStmt->bind_param("ssi", $tokenHash, $expiresAt, $user['id']);
    $updateStmt->execute();

    // In production, you would send an email here
    // For testing, we'll return the token (remove in production!)
    die(json_encode([
        'success' => true,
        'message' => 'Password reset link would be sent to your email in production',
        'debug_token' => $token // Remove this line in production!
    ]));

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'An error occurred']));
} finally {
    // Close connection and clean buffer
    if (isset($conn)) $conn->close();
    ob_end_clean();
}
?>