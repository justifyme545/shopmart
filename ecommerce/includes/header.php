<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopMart - Nigerian E-commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- Navigation -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            
            <!-- Logo -->
            <a href="index.php" class="text-2xl font-bold">
                Shop<span class="text-green-600">Mart</span>
            </a>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-700 hover:text-black">Home</a>
                <a href="shop.php" class="text-gray-700 hover:text-black">Shop</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="account.php" class="text-gray-700 hover:text-black">My Account</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="../admin/dashboard.php" class="text-red-600 hover:text-red-800">Admin Panel</a>
                <?php endif; ?>
            </div>
            
            <!-- Right Side Icons -->
            <div class="flex items-center space-x-4">
                
                <!-- Cart Icon -->
                <a href="cart.php" class="relative">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M5 21h.01M19 21h.01"></path>
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">
                        <?php 
                        $cart_count = 0;
                        if (isset($_SESSION['cart'])) {
                            $cart_count = array_sum($_SESSION['cart']);
                        }
                        echo $cart_count;
                        ?>
                    </span>
                </a>
                
                <!-- User Menu -->
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700">
                            <span>👤</span>
                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl hidden group-hover:block">
                            <a href="account.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="orders.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Orders</a>
                            <hr class="my-1">
                            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center space-x-3">
                        <a href="login.php" class="text-gray-700 hover:text-black">Login</a>
                        <a href="register.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            Sign Up
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>
            
        </div>
    </div>
</nav>

<main class="min-h-screen">