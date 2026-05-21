<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';
include 'includes/admin-header.php';

// Get product ID from URL
$id = $_GET['id'] ?? 0;

if (!$id) {
    header('Location: products.php');
    exit();
}

// Fetch product details
$stmt = query("SELECT * FROM products WHERE id = ?", [$id], "i");
$product = fetch($stmt);

if (!$product) {
    header('Location: products.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    
    // Handle image upload
    $image_name = $product['image']; // Keep existing image by default
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            // Delete old image if exists
            if ($product['image'] && file_exists('../assets/images/' . $product['image'])) {
                unlink('../assets/images/' . $product['image']);
            }
            
            // Upload new image
            $image_name = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $image_name);
        }
    }
    
    // Update product
    $stmt = query(
        "UPDATE products SET name = ?, price = ?, category = ?, stock = ?, description = ?, status = ?, image = ? WHERE id = ?",
        [$name, $price, $category, $stock, $description, $status, $image_name, $id],
        "sdsisssi"
    );
    
    if ($stmt) {
        header('Location: products.php?updated=1');
        exit();
    } else {
        $error = "Failed to update product. Please try again.";
    }
}
?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Product</h1>
        <a href="products.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            ← Back to Products
        </a>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 max-w-2xl">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Product Name *</label>
            <input type="text" name="name" required 
                   value="<?php echo htmlspecialchars($product['name']); ?>"
                   class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-2 font-medium">Price (₦) *</label>
                <input type="number" step="0.01" name="price" required 
                       value="<?php echo $product['price']; ?>"
                       class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
            </div>
            <div>
                <label class="block text-gray-700 mb-2 font-medium">Stock Quantity *</label>
                <input type="number" name="stock" required 
                       value="<?php echo $product['stock']; ?>"
                       class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-2 font-medium">Category *</label>
                <select name="category" required class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="Fashion" <?php echo $product['category'] == 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                    <option value="Gadgets" <?php echo $product['category'] == 'Gadgets' ? 'selected' : ''; ?>>Gadgets</option>
                    <option value="Jewelries" <?php echo $product['category'] == 'Jewelries' ? 'selected' : ''; ?>>Jewelries</option>
                    <option value="Sportwears" <?php echo $product['category'] == 'Sportwears' ? 'selected' : ''; ?>>Sportwears</option>
                    <option value="Wristwatches" <?php echo $product['category'] == 'Wristwatches' ? 'selected' : ''; ?>>Wristwatches</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2 font-medium">Status *</label>
                <select name="status" required class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Current Image</label>
            <?php if($product['image'] && file_exists('../assets/images/' . $product['image'])): ?>
                <img src="../assets/images/<?php echo $product['image']; ?>" 
                     class="w-32 h-32 object-cover rounded-lg mb-2">
                <p class="text-sm text-gray-500">Current: <?php echo $product['image']; ?></p>
            <?php else: ?>
                <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-gray-500">No image</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">New Image (optional)</label>
            <input type="file" name="image" accept="image/*" 
                   class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
            <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Allowed: JPG, PNG, GIF, WEBP</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2 font-medium">Description</label>
            <textarea name="description" rows="5" 
                      class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                Update Product
            </button>
            <a href="products.php" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition">
                Cancel
            </a>
        </div>
        
    </form>
</div>

<?php include 'includes/admin-footer.php'; ?>