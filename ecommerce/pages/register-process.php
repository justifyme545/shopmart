<?php
require_once '../includes/db.php';
session_start();

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    $_SESSION['error'] = 'Please fill in all fields';
    header('Location: register.php');
    exit();
}

if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password must be at least 6 characters';
    header('Location: register.php');
    exit();
}

// Split name into first and last
$name_parts = explode(' ', $name, 2);
$first_name = $name_parts[0];
$last_name = $name_parts[1] ?? '';

// Check if email exists
$stmt = query("SELECT id FROM users WHERE email = ?", [$email], "s");
if (fetch($stmt)) {
    $_SESSION['error'] = 'Email already registered';
    header('Location: register.php');
    exit();
}

// Create user
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = query(
    "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)",
    [$first_name, $last_name, $email, $hashed_password],
    "ssss"
);

if ($stmt) {
    $_SESSION['user_id'] = lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    header('Location: account.php');
} else {
    $_SESSION['error'] = 'Registration failed. Please try again.';
    header('Location: register.php');
}
exit();
?>