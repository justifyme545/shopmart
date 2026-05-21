<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../includes/header.php';

$payment_type = $_GET['payment'] ?? '';

// Clear cart if not already cleared
unset($_SESSION['cart']);
unset($_SESSION['pending_order']);
?>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-6 py-20">
    
    <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-md">
        
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold mb-4">Order Placed Successfully! 🎉</h1>
        
        <?php if($payment_type == 'cod'): ?>
            <p class="text-gray-600 mb-4">
                Thank you for your order! You will pay cash upon delivery.
            </p>
        <?php elseif($payment_type == 'bank'): ?>
            <p class="text-gray-600 mb-4">
                Thank you for your order! We will process your order after payment confirmation.
            </p>
        <?php elseif($payment_type == 'card'): ?>
            <p class="text-gray-600 mb-4">
                Payment successful! Your order has been confirmed.
            </p>
        <?php else: ?>
            <p class="text-gray-600 mb-4">
                Your order has been received and is being processed.
            </p>
        <?php endif; ?>
        
        <p class="text-gray-500 mb-8">
            A confirmation email has been sent to your email address.
        </p>
        
        <div class="space-y-3">
            <a href="account.php" 
               class="block w-full bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800 transition">
                View My Orders
            </a>
            
            <a href="shop.php" 
               class="block w-full border border-gray-300 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-50 transition">
                Continue Shopping
            </a>
        </div>
        
    </div>
    
</div>

<?php include '../includes/footer.php'; ?>