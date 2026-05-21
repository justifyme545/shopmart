<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/db.php';
include '../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$cartItems = [];
$total = 0;
$error = '';

// Fetch actual product data
if (!empty($cart)) {
    foreach ($cart as $id => $qty) {
        $stmt = query("SELECT * FROM products WHERE id = ?", [$id], "i");
        $product = fetch($stmt);
        if ($product) {
            $product['qty'] = $qty;
            $cartItems[] = $product;
            $total += $product['price'] * $qty;
        }
    }
}

// Process checkout with payment validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($address) || empty($phone)) {
        $error = 'Please fill in all billing details';
    } elseif (empty($payment_method)) {
        $error = 'Please select a payment method';
    } else {
        // Create order (pending payment)
        $order_number = 'ORD-' . time() . '-' . rand(100, 999);
        $stmt = query(
            "INSERT INTO orders (user_id, order_number, total_amount, shipping_address, shipping_city, shipping_zip, payment_method, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')",
            [$_SESSION['user_id'], $order_number, $total + 5000, $address, 'N/A', 'N/A', $payment_method],
            "isdssss"
        );
        
        if ($stmt) {
            $order_id = lastInsertId();
            
            // Add order items
            foreach ($cartItems as $item) {
                query(
                    "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)",
                    [$order_id, $item['id'], $item['qty'], $item['price']],
                    "iiid"
                );
            }
            
            // If payment method is 'cash_on_delivery', mark as processing
            if ($payment_method === 'cash_on_delivery') {
                query("UPDATE orders SET status = 'processing' WHERE id = ?", [$order_id], "i");
                // Clear cart
                unset($_SESSION['cart']);
                header('Location: success.php?payment=cod');
                exit();
            } 
            // If payment method is 'bank_transfer', show payment instructions
            elseif ($payment_method === 'bank_transfer') {
                $_SESSION['pending_order'] = $order_id;
                header('Location: payment-instructions.php');
                exit();
            }
            // For card payment (simulated)
            elseif ($payment_method === 'card') {
                // In real world, you'd integrate with Paystack, Flutterwave, etc.
                $_SESSION['pending_order'] = $order_id;
                header('Location: card-payment.php');
                exit();
            }
        } else {
            $error = 'Failed to process order. Please try again.';
        }
    }
}

// Redirect if cart is empty
if (empty($cartItems)) {
    header('Location: cart.php');
    exit();
}
?>

<div class="min-h-screen bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10">
        
        <!-- BILLING -->
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <h2 class="text-3xl font-bold mb-8">Billing Details</h2>
            
            <?php if($error): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="checkoutForm">
                <div class="mb-5">
                    <label class="block mb-2 font-medium">Full Name</label>
                    <input type="text" name="name" required
                           class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <div class="mb-5">
                    <label class="block mb-2 font-medium">Address</label>
                    <input type="text" name="address" required
                           class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <div class="mb-5">
                    <label class="block mb-2 font-medium">Phone Number</label>
                    <input type="tel" name="phone" required
                           class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2 font-medium">Payment Method</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash_on_delivery" required class="mr-3">
                            <div>
                                <span class="font-semibold">Cash on Delivery</span>
                                <p class="text-sm text-gray-500">Pay when you receive the order</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" required class="mr-3">
                            <div>
                                <span class="font-semibold">Bank Transfer</span>
                                <p class="text-sm text-gray-500">Pay via bank transfer</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="card" required class="mr-3">
                            <div>
                                <span class="font-semibold">Card Payment</span>
                                <p class="text-sm text-gray-500">Pay with debit/credit card</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-black text-white py-4 rounded-xl hover:bg-gray-800 transition">
                    Place Order - ₦<?php echo number_format($total + 5000, 2); ?>
                </button>
            </form>
        </div>
        
        <!-- SUMMARY -->
        <div class="bg-white rounded-3xl shadow-xl p-8 h-fit">
            <h2 class="text-3xl font-bold mb-8">Order Summary</h2>
            
            <?php foreach($cartItems as $item): ?>
            <div class="flex justify-between border-b py-4">
                <div>
                    <h3 class="font-semibold"><?php echo e($item['name']); ?></h3>
                    <p class="text-gray-500">Qty: <?php echo $item['qty']; ?></p>
                </div>
                <div class="font-bold">
                    ₦<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="flex justify-between border-b py-4">
                <span>Subtotal</span>
                <span>₦<?php echo number_format($total, 2); ?></span>
            </div>
            
            <div class="flex justify-between border-b py-4">
                <span>Shipping</span>
                <span>₦5,000.00</span>
            </div>
            
            <div class="flex justify-between text-2xl font-bold mt-8 pt-4 border-t">
                <span>Total</span>
                <span>₦<?php echo number_format($total + 5000, 2); ?></span>
            </div>
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>