<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';  // This should work now

// Get statistics
$total_products = fetch(query("SELECT COUNT(*) as count FROM products"))['count'];
$total_orders = fetch(query("SELECT COUNT(*) as count FROM orders"))['count'];
$total_users = fetch(query("SELECT COUNT(*) as count FROM users WHERE role = 'user'"))['count'];
$total_revenue = fetch(query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'"))['total'] ?? 0;

// Recent orders
$stmt = query("SELECT o.*, u.first_name, u.last_name FROM orders o 
               JOIN users u ON o.user_id = u.id 
               ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = all($stmt);

// Low stock products
$stmt = query("SELECT * FROM products WHERE stock < 10 AND status = 'active' ORDER BY stock ASC LIMIT 5");
$low_stock = all($stmt);
?>

<div>
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Products</p>
                    <p class="text-3xl font-bold"><?php echo $total_products; ?></p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-3xl font-bold"><?php echo $total_orders; ?></p>
                </div>
                <div class="text-4xl">🛒</div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-3xl font-bold"><?php echo $total_users; ?></p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Revenue</p>
                    <p class="text-3xl font-bold">₦<?php echo number_format($total_revenue, 2); ?></p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left">
                        <th class="p-3">Order #</th>
                        <th class="p-3">Customer</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_orders as $order): ?>
                    <tr class="border-t">
                        <td class="p-3">#<?php echo $order['order_number']; ?></td>
                        <td class="p-3"><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                        <td class="p-3">₦<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs 
                                <?php echo $order['status'] == 'delivered' ? 'bg-green-100 text-green-700' : 
                                          ($order['status'] == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td class="p-3"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <?php if(!empty($low_stock)): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-red-600">Low Stock Alert</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left">
                        <th class="p-3">Product</th>
                        <th class="p-3">Current Stock</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($low_stock as $product): ?>
                    <tr class="border-t">
                        <td class="p-3"><?php echo $product['name']; ?></td>
                        <td class="p-3 text-red-600 font-bold"><?php echo $product['stock']; ?> left</td>
                        <td class="p-3">
                            <a href="product-edit.php?id=<?php echo $product['id']; ?>" class="text-blue-600 hover:underline">Update Stock</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/admin-footer.php'; ?>