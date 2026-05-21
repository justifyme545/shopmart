<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/db.php';
include '../includes/header.php';

$order_id = $_SESSION['pending_order'] ?? 0;

if (!$order_id) {
    header('Location: cart.php');
    exit();
}

// Get order details
$stmt = query("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$order_id, $_SESSION['user_id']], "ii");
$order = fetch($stmt);
?>

<div class="min-h-screen bg-gray-100 py-16">
    <div class="max-w-2xl mx-auto px-6">
        
        <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
            
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="text-4xl">🏦</span>
            </div>
            
            <h1 class="text-3xl font-bold mb-4">Bank Transfer Instructions</h1>
            
            <p class="text-gray-600 mb-8">
                Please make payment to the following bank account. Your order will be processed after payment confirmation.
            </p>
            
            <div class="bg-gray-50 rounded-xl p-6 text-left mb-8">
                <h3 class="font-bold text-lg mb-4">Bank Details:</h3>
                <p><strong>Bank:</strong> First Bank of Nigeria</p>
                <p><strong>Account Name:</strong> ShopMart Enterprises</p>
                <p><strong>Account Number:</strong> 1234567890</p>
                <p><strong>Amount:</strong> ₦<?php echo number_format($order['total_amount'], 2); ?></p>
                <p><strong>Order Reference:</strong> <?php echo $order['order_number']; ?></p>
            </div>
            
            <div class="bg-yellow-50 rounded-xl p-4 mb-8 text-left">
                <p class="text-sm text-yellow-800">
                    ⚠️ After payment, please send proof of payment to: justicefest@gmail.com or WhatsApp: +2348066227971
                </p>
            </div>
            
            <div class="space-y-3">
                <a href="success.php?payment=bank" 
                   class="block w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 text-center">
                    I Have Made Payment
                </a>
                <a href="shop.php" 
                   class="block w-full border border-gray-300 text-gray-700 py-3 rounded-xl hover:bg-gray-50 text-center">
                    Continue Shopping
                </a>
            </div>
            
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>