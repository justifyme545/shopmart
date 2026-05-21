<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    query("UPDATE orders SET status = ? WHERE id = ?", [$status, $order_id], "si");
    header('Location: orders.php?updated=1');
    exit();
}

// Fetch orders
$stmt = query("SELECT o.*, u.first_name, u.last_name, u.email FROM orders o 
               JOIN users u ON o.user_id = u.id 
               ORDER BY o.created_at DESC");
$orders = all($stmt);
?>

<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Order Management</h1>
    
    <?php if(isset($_GET['updated'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">Order status updated successfully!</div>
    <?php endif; ?>
    
    <?php if(empty($orders)): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">No orders found.</p>
        </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">Order #</th>
                    <th class="p-3">Customer</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-mono">#<?php echo $order['order_number']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($order['email']); ?></td>
                    <td class="p-3">₦<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="p-3">
                        <form method="POST" class="inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td class="p-3"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    <td class="p-3">
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="text-blue-600 hover:underline">View Details</a>
                    </td>
                 </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-footer.php'; ?>