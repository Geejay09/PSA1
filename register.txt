<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Database config
$host = 'localhost';
$db = 'dbpsa';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$username = $_POST['username'];
$password = $_POST['password'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if username exists
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Username already taken'); window.location.href='register.html';</script>";
} else {
    // Insert user
    $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Registration error'); window.location.href='register.html';</script>";
    }
}

$stmt->close();
$conn->close();
?>
