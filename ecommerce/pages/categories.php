<?php
session_start();
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';

if (!$slug) {
    die("Category not found");
}

// Get category
$stmt = query("SELECT * FROM categories WHERE slug = ?", [$slug], "s");
$category = fetch($stmt);

if (!$category) {
    die("Category not found");
}

// Get products
$stmt = query("SELECT * FROM products WHERE category = ?", [$category['name']], "s");
$products = all($stmt);

include '../includes/header.php';
?>

<div class="bg-gray-50 min-h-screen">

    <!-- HERO -->
    <section class="bg-black text-white py-16 text-center">

        <h1 class="text-5xl font-bold">
            <?php echo e($category['name']); ?>
        </h1>

        <p class="text-gray-300 mt-4">
            Explore our collection of <?php echo e($category['name']); ?>
        </p>

    </section>

    <!-- PRODUCTS -->
    <section class="max-w-7xl mx-auto px-6 py-16">

        <?php if(count($products) > 0): ?>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <?php foreach($products as $p): ?>

            <div class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">

                <img src="../assets/images/<?php echo e($p['image']); ?>" 
                     class="h-64 w-full object-cover"
                     onerror="this.src='https://via.placeholder.com/400'">

                <div class="p-4">

                    <h3 class="font-semibold text-lg">
                        <?php echo e($p['name']); ?>
                    </h3>

                    <p class="text-gray-500 text-sm mt-1">
                        <?php 
                        $desc = !empty($p['description']) ? $p['description'] : 'Premium quality product';
                        echo e($desc); 
                        ?>
                    </p>

                    <div class="mt-4 flex justify-between items-center">

                        <span class="font-bold text-xl">
                            $<?php echo number_format($p['price'], 2); ?>
                        </span>

                        <a href="product.php?id=<?php echo $p['id']; ?>" 
                           class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800">
                            View
                        </a>

                    </div>
                    
                    <form method="POST" action="cart.php" class="mt-3">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        <button class="w-full bg-gray-100 hover:bg-black hover:text-white transition py-2 rounded-lg">
                            Add to Cart
                        </button>
                    </form>

                </div>

            </div>

            <?php endforeach; ?>

        </div>
        
        <?php else: ?>
        
        <div class="text-center py-20 bg-white rounded-2xl">
            <h2 class="text-2xl font-bold mb-4">No Products Found</h2>
            <p class="text-gray-500 mb-6">This category has no products yet.</p>
            <a href="shop.php" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800">
                Browse All Products
            </a>
        </div>
        
        <?php endif; ?>

    </section>

</div>

<?php include '../includes/footer.php'; ?>