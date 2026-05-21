<?php
require_once '../includes/auth.php';

echo "<h1>Session Debug</h1>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Data:\n";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user_name'])) {
    echo "<p style='color:green'>✅ User is logged in as: " . $_SESSION['user_name'] . "</p>";
} else {
    echo "<p style='color:red'>❌ No user logged in</p>";
}

echo "<br><a href='index.php'>Go to Home</a> | ";
echo "<a href='cart.php'>Go to Cart</a> | ";
echo "<a href='logout.php'>Logout</a>";
?>