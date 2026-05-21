<?php
require_once '../includes/db.php';

// Hash the password
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// First, add role column if not exists
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user', 'admin') DEFAULT 'user'";
mysqli_query($conn, $sql);

// Delete existing admin
$stmt = query("DELETE FROM users WHERE email = 'admin@shopmart.com'");

// Insert new admin
$stmt = query(
    "INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)",
    ['Admin', 'User', 'admin@shopmart.com', $hashed_password, 'admin'],
    "sssss"
);

if ($stmt) {
    echo "✅ Admin account created successfully!<br>";
    echo "Email: admin@shopmart.com<br>";
    echo "Password: admin123<br>";
    echo "<a href='login.php'>Click here to login</a>";
} else {
    echo "❌ Error creating admin account: " . mysqli_error($conn);
}
?>