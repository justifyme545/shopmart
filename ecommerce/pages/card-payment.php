<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/db.php';
include '../includes/header.php';

$order_id = $_SESSION['pending_order'] ?? 0;
$error = '';

if (!$order_id) {
    header('Location: cart.php');
    exit();
}

// Get order details
$stmt = query("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$order_id, $_SESSION['user_id']], "ii");
$order = fetch($stmt);

// Process card payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    
    // Basic validation
    if (empty($card_number) || empty($expiry) || empty($cvv)) {
        $error = 'Please fill in all card details';
    } elseif (strlen($card_number) < 16) {
        $error = 'Invalid card number';
    } elseif (strlen($cvv) < 3) {
        $error = 'Invalid CVV';
    } else {
        // Simulate payment processing
        // In production, integrate with Paystack, Flutterwave, etc.
        
        // Update order status
        query("UPDATE orders SET status = 'processing' WHERE id = ?", [$order_id], "i");
        
        // Clear cart and session
        unset($_SESSION['cart']);
        unset($_SESSION['pending_order']);
        
        header('Location: success.php?payment=card');
        exit();
    }
}
?>

<div class="min-h-screen bg-gray-100 py-16">
    <div class="max-w-md mx-auto px-6">
        
        <div class="bg-white rounded-3xl shadow-xl p-8">
            
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">💳</span>
                </div>
                <h1 class="text-2xl font-bold">Card Payment</h1>
                <p class="text-gray-600 mt-2">Amount: ₦<?php echo number_format($order['total_amount'], 2); ?></p>
            </div>
            
            <?php if($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-6">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Card Number</label>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required
                           maxlength="19" class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Expiry Date</label>
                        <input type="text" name="expiry" placeholder="MM/YY" required
                               class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">CVV</label>
                        <input type="password" name="cvv" placeholder="123" required
                               maxlength="4" class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 transition">
                    Pay ₦<?php echo number_format($order['total_amount'], 2); ?>
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <a href="checkout.php" class="text-gray-500 hover:underline">← Back to Checkout</a>
            </div>
            
            <div class="mt-6 pt-6 border-t text-center">
                <p class="text-xs text-gray-400">
                    This is a demo. In production, integrate with Paystack, Flutterwave, or other payment gateways.
                </p>
            </div>
            
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>