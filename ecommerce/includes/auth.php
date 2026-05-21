<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Require login (redirect if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Please login to continue';
        header('Location: login.php');
        exit();
    }
}

// Require admin access
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['error'] = 'Access denied. Admin only.';
        header('Location: ../pages/index.php');
        exit();
    }
}

// Alias for authRequired (used in checkout.php)
function authRequired() {
    requireLogin();
}

// Redirect if already logged in
function guestOnly() {
    if (isLoggedIn()) {
        if (isAdmin()) {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: account.php');
        }
        exit();
    }
}

// Logout function
function logout() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    session_destroy();
    header('Location: login.php');
    exit();
}

// Get current user
function getCurrentUser() {
    if (isLoggedIn()) {
        global $conn;
        $stmt = query("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']], "i");
        return fetch($stmt);
    }
    return null;
}
?>