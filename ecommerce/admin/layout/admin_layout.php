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
        <div class="w-64 bg-black text-white fixed h-full">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
                <p class="text-gray-400 text-sm mt-1">Welcome, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?></p>
            </div>
            
            <nav class="mt-6">
                <a href="dashboard.php" class="block px-6 py-3 hover:bg-gray-800 transition">
                    📊 Dashboard
                </a>
                <a href="products.php" class="block px-6 py-3 hover:bg-gray-800 transition">
                    📦 Products
                </a>
                <a href="orders.php" class="block px-6 py-3 hover:bg-gray-800 transition">
                    🛒 Orders
                </a>
                <a href="users.php" class="block px-6 py-3 hover:bg-gray-800 transition">
                    👥 Users
                </a>
                <hr class="my-4 border-gray-700">
                <a href="../pages/logout.php" class="block px-6 py-3 hover:bg-gray-800 transition text-red-400">
                    🚪 Logout
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <?php echo $content; ?>
        </div>
    </div>
</body>
</html>