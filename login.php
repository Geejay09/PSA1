<?php
header('Content-Type: application/json');
session_start();

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'dbpsa';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get credentials
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    // Find active user (case-insensitive search)
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, email, password, position, access_level 
        FROM tbl_employees 
        WHERE LOWER(email) = LOWER(:email) AND is_deleted = 0
    ");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['position'] = $user['position'];
            $_SESSION['access_level'] = $user['access_level'];
            $_SESSION['logged_in'] = true;

            // Successful login
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'position' => $user['position'],
                    'access_level' => $user['access_level']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found or account inactive']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    error_log("Login error: " . $e->getMessage());
}
?>