<?php
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ShopMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-black text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
                <p class="text-gray-400 text-sm mt-1">Welcome, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
            </div>
            
            <nav class="mt-6">
                <a href="dashboard.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-gray-800' : ''; ?>">
                    📊 Dashboard
                </a>
                <a href="products.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo strpos($_SERVER['PHP_SELF'], 'product') !== false ? 'bg-gray-800' : ''; ?>">
                    📦 Products
                </a>
                <a href="orders.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'bg-gray-800' : ''; ?>">
                    🛒 Orders
                </a>
                <a href="users.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-gray-800' : ''; ?>">
                    👥 Users
                </a>
                <a href="reviews.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'bg-gray-800' : ''; ?>">
                    ⭐ Reviews
                </a>
                <a href="settings.php" class="block px-6 py-3 hover:bg-gray-800 transition <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-800' : ''; ?>">
                    ⚙️ Settings
                </a>
                <hr class="my-4 border-gray-700">
                <a href="../pages/logout.php" class="block px-6 py-3 hover:bg-gray-800 transition text-red-400">
                    🚪 Logout
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">