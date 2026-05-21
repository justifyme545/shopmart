<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'ecommerce';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Execute query with prepared statement
function query($sql, $params = [], $types = "") {
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        die("Query preparation failed: " . mysqli_error($conn));
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    return $stmt;
}

// Fetch single row
function fetch($stmt) {
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Fetch all rows
function all($stmt) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Alias for all() function
function fetchAll($stmt) {
    return all($stmt);
}

// Escape string for safe output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Get last inserted ID
function lastInsertId() {
    global $conn;
    return mysqli_insert_id($conn);
}
// Format price in Naira
function formatPrice($price) {
    return '₦' . number_format($price, 2);
}
?>