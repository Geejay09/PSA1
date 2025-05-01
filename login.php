<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$db = 'dbpsa';
$user = 'root';
$pass = '';

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get posted credentials
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Both email and password are required.']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM tbl_employees WHERE email = :email AND is_deleted = 0");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() === 1) {
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if (password_verify($password, $employee['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $employee['id'];
            $_SESSION['email'] = $employee['email'];
            $_SESSION['first_name'] = $employee['first_name'];
            $_SESSION['last_name'] = $employee['last_name'];
            $_SESSION['position'] = $employee['position'];
            $_SESSION['access_level'] = $employee['access_level'];
            $_SESSION['logged_in'] = true;
            
            // Successful login response
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful. Redirecting...',
                'user' => [
                    'name' => $employee['first_name'] . ' ' . $employee['last_name'],
                    'position' => $employee['position']
                ]
            ]);
        } else {
            // Incorrect password
            echo json_encode(['success' => false, 'message' => 'Incorrect password. Please try again.']);
        }
    } else {
        // No user found
        echo json_encode(['success' => false, 'message' => 'No account found with this email address.']);
    }
} catch(PDOException $e) {
    // Database error
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
    // For debugging: 
    // error_log("Database error: " . $e->getMessage());
}
?>