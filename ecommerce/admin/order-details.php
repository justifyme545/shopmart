<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';

$order_id = $_GET['id'] ?? 0;

if (!$order_id) {
    header('Location: orders.php');
    exit();
}

// Get order details
$stmt = query("SELECT o.*, u.first_name, u.last_name, u.email, u.phone, u.address 
               FROM orders o 
               JOIN users u ON o.user_id = u.id 
               WHERE o.id = ?", [$order_id], "i");
$order = fetch($stmt);

if (!$order) {
    header('Location: orders.php');
    exit();
}

// Get order items
$stmt = query("SELECT oi.*, p.name, p.image 
               FROM order_items oi 
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.order_id = ?", [$order_id], "i");
$items = all($stmt);
?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order Details</h1>
        <a href="orders.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            ← Back to Orders
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Order Information</h2>
            
            <div class="space-y-2">
                <p><strong>Order Number:</strong> #<?php echo $order['order_number']; ?></p>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
                <p><strong>Order Status:</strong> 
                    <span class="px-2 py-1 rounded-full text-xs 
                        <?php echo $order['status'] == 'delivered' ? 'bg-green-100 text-green-700' : 
                                  ($order['status'] == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </p>
                <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                <p><strong>Total Amount:</strong> <span class="font-bold text-xl">₦<?php echo number_format($order['total_amount'], 2); ?></span></p>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Customer Information</h2>
            
            <div class="space-y-2">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></p>
                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['shipping_address'] ?? 'N/A')); ?></p>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Order Items</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="p-3">Product</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr class="border-t">
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    <?php if($item['image'] && file_exists('../assets/images/' . $item['image'])): ?>
                                        <img src="../assets/images/<?php echo $item['image']; ?>" class="w-12 h-12 object-cover rounded">
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td class="p-3">₦<?php echo number_format($item['price'], 2); ?></td>
                            <td class="p-3"><?php echo $item['quantity']; ?></td>
                            <td class="p-3">₦<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr class="border-t">
                            <td colspan="3" class="p-3 text-right font-bold">Total:</td>
                            <td class="p-3 font-bold">₦<?php echo number_format($order['total_amount'], 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>