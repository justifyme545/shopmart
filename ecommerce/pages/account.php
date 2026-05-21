<?php
require_once '../includes/auth.php';
requireLogin();
require_once '../includes/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = query("SELECT * FROM users WHERE id = ?", [$user_id], "i");
$user = fetch($stmt);

// Get user orders
$stmt = query("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC", [$user_id], "i");
$orders = all($stmt);
?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="bg-white rounded-2xl shadow p-8">
            <h1 class="text-3xl font-bold mb-4">My Account</h1>
            <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
            
            <div class="mt-8 grid md:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Account Information</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Member since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>
                
                <div>
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    <a href="logout.php" class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</a>
                    <a href="shop.php" class="inline-block bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 ml-2">Continue Shopping</a>
                </div>
            </div>
            
            <?php if (!empty($orders)): ?>
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-2 text-left">Order #</th>
                                <th class="p-2 text-left">Date</th>
                                <th class="p-2 text-left">Total</th>
                                <th class="p-2 text-left">Status</th>
                             </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
                            <tr class="border-t">
                                <td class="p-2">#<?php echo $order['order_number']; ?></td>
                                <td class="p-2"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td class="p-2">₦<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td class="p-2">
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                             </tr>
                            <?php endforeach; ?>
                        </tbody>
                     </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>