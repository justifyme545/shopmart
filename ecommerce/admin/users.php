<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    query("DELETE FROM users WHERE id = ? AND role = 'user'", [$id], "i");
    header('Location: users.php?deleted=1');
    exit();
}

// Fetch users
$stmt = query("SELECT * FROM users ORDER BY created_at DESC");
$users = all($stmt);
?>

<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">User Management</h1>
    
    <?php if(isset($_GET['deleted'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">User deleted successfully!</div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Registered</th>
                    <th class="p-3">Actions</th>
                 </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr class="border-t">
                    <td class="p-3"><?php echo $user['id']; ?></td>
                    <td class="p-3"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                    <td class="p-3"><?php echo $user['email']; ?></td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs <?php echo $user['role'] == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td class="p-3"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    <td class="p-3">
                        <?php if($user['role'] != 'admin'): ?>
                            <a href="?delete=<?php echo $user['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this user?')">Delete</a>
                        <?php else: ?>
                            <span class="text-gray-400">Admin</span>
                        <?php endif; ?>
                    </td>
                 </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>