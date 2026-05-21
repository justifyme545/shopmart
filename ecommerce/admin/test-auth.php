<?php
require_once '../includes/auth.php';

echo "<h1>Testing Auth Functions</h1>";

// Check if function exists
if (function_exists('requireAdmin')) {
    echo "✅ requireAdmin() function exists<br>";
} else {
    echo "❌ requireAdmin() function does NOT exist<br>";
}

if (function_exists('isAdmin')) {
    echo "✅ isAdmin() function exists<br>";
} else {
    echo "❌ isAdmin() function does NOT exist<br>";
}

echo "<br>Current Session:<br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<br>Try to call requireAdmin()...<br>";
// This will redirect if not admin
// requireAdmin();
?>