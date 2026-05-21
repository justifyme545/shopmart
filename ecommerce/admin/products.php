<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First, get the product image to delete it from folder
    $stmt = query("SELECT image FROM products WHERE id = ?", [$id], "i");
    $product = fetch($stmt);
    
    if ($product) {
        // Delete image file if exists
        if (!empty($product['image']) && file_exists('../assets/images/' . $product['image'])) {
            unlink('../assets/images/' . $product['image']);
        }
        
        // Delete product from database
        $stmt = query("DELETE FROM products WHERE id = ?", [$id], "i");
        
        if ($stmt) {
            header('Location: products.php?deleted=1');
            exit();
        } else {
            header('Location: products.php?error=1');
            exit();
        }
    } else {
        header('Location: products.php?notfound=1');
        exit();
    }
}

// Fetch products
$stmt = query("SELECT * FROM products ORDER BY id DESC");
$products = all($stmt);
?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Products Management</h1>
        <a href="product-add.php" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            + Add New Product
        </a>
    </div>
    
    <?php if(isset($_GET['deleted'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            ✅ Product deleted successfully!
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            ❌ Failed to delete product. Please try again.
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['notfound'])): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-4">
            ⚠️ Product not found.
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['updated'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            ✅ Product updated successfully!
        </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['added'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            ✅ Product added successfully!
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="p-3">ID</th>
                    <th class="p-3">Image</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Stock</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($products)): ?>
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            No products found. <a href="product-add.php" class="text-blue-600 hover:underline">Add your first product</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($products as $product): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3"><?php echo $product['id']; ?></td>
                        <td class="p-3">
                            <?php if(!empty($product['image']) && file_exists('../assets/images/' . $product['image'])): ?>
                                <img src="../assets/images/<?php echo $product['image']; ?>" 
                                     class="w-12 h-12 object-cover rounded"
                                     onerror="this.src='https://via.placeholder.com/48'">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">
                                    No img
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-3 font-medium"><?php echo htmlspecialchars($product['name']); ?></td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100">
                                <?php echo $product['category']; ?>
                            </span>
                         </td>
                        <td class="p-3 font-semibold">₦<?php echo number_format($product['price'], 2); ?></td>
                        <td class="p-3">
                            <span class="<?php echo $product['stock'] < 10 ? 'text-red-600 font-bold' : 'text-gray-700'; ?>">
                                <?php echo $product['stock']; ?>
                            </span>
                         </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs <?php echo $product['status'] == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                         </td>
                        <td class="p-3">
                            <a href="product-edit.php?id=<?php echo $product['id']; ?>" 
                               class="text-blue-600 hover:text-blue-800 mr-3 inline-block">
                                📝 Edit
                            </a>
                            <a href="javascript:void(0)" 
                               onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>')"
                               class="text-red-600 hover:text-red-800 inline-block">
                                🗑️ Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
         </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete "' + name + '"? This action cannot be undone.')) {
        window.location.href = '?delete=' + id;
    }
}
</script>

<?php include 'includes/admin-footer.php'; ?>