<?php
require_once '../includes/db.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Please fill in all fields';
    header('Location: login.php');
    exit();
}

$stmt = query("SELECT * FROM users WHERE email = ?", [$email], "s");
$user = fetch($stmt);

if ($user && password_verify($password, $user['password'])) {
    // Set all session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_first_name'] = $user['first_name'];
    
    // Debug - verify session is set
    error_log("User logged in: ID=" . $user['id'] . ", Name=" . $_SESSION['user_name']);
    
    // Redirect based on role
    if ($user['role'] === 'admin') {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
} else {
    $_SESSION['error'] = 'Invalid email or password';
    header('Location: login.php');
    exit();
}
?>