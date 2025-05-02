<?php
// Ensure no output before headers
if (ob_get_length()) ob_clean();

header('Content-Type: application/json');
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input
if (json_last_error() !== JSON_ERROR_NONE || !isset($data['token']) || !isset($data['password'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request data']));
}

$token = $data['token'];
$new_password = $data['password'];

try {
    // Verify token
    $token_hash = hash('sha256', $token);
    $stmt = $pdo->prepare("SELECT id, reset_token_expires_at FROM tbl_employees WHERE reset_token = ? AND is_deleted = 0");
    $stmt->execute([$token_hash]);
    $employee = $stmt->fetch();

    if (!$employee) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Invalid or expired token']));
    }

    // Check expiration
    if (strtotime($employee['reset_token_expires_at']) <= time()) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Token has expired']));
    }

    // Validate password (add your own requirements)
    if (strlen($new_password) < 8) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
    }

    // Hash new password
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password and clear token
    $stmt = $pdo->prepare("UPDATE tbl_employees SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
    $stmt->execute([$password_hash, $employee['id']]);

    die(json_encode(['success' => true, 'message' => 'Password updated successfully']));
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'An error occurred']));
}
?>