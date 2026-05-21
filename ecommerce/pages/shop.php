<?php
//session_start();
require_once '../includes/db.php';
include '../includes/header.php';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query dynamically
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

// Category filter
if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// Search filter
if (!empty($search)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

// Fetch products
$stmt = query($sql, $params, $types);
$products = all($stmt);
?>

<div class="bg-gray-50 min-h-screen">

    <!-- HERO -->
    <section class="bg-black text-white py-16">

        <div class="max-w-7xl mx-auto px-6">

            <h1 class="text-5xl font-bold mb-4">
                Shop Collection
            </h1>

            <p class="text-gray-300 text-lg">
                Discover premium fashion, gadgets, jewelry,
                sportswear, watches and more.
            </p>

        </div>

    </section>

    <!-- FILTERS -->
    <section class="max-w-7xl mx-auto px-6 py-10">

        <div class="bg-white rounded-2xl shadow p-6">

            <form method="GET"
                  class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search products..."
                    class="border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black"
                >

                <!-- Category -->
                <select
                    name="category"
                    class="border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-black"
                >
                    <option value="">All Categories</option>

                    <?php
                    $categories = [
                        "Fashion",
                        "Gadgets",
                        "Jewelries",
                        "Sportwears",
                        "Wristwatches"
                    ];

                    foreach($categories as $cat):
                    ?>

                    <option
                        value="<?php echo $cat; ?>"
                        <?php if($category === $cat) echo "selected"; ?>
                    >
                        <?php echo $cat; ?>
                    </option>

                    <?php endforeach; ?>

                </select>

                <!-- Submit -->
                <button
                    class="bg-black text-white rounded-xl px-6 py-3 hover:bg-gray-800 transition"
                >
                    Filter Products
                </button>

            </form>

        </div>
        
        <!-- Category Quick Links -->
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="shop.php" 
               class="px-3 py-1 bg-gray-200 rounded-full text-sm hover:bg-black hover:text-white transition">
                All
            </a>
            <?php
            $quickCategories = ['Fashion', 'Gadgets', 'Jewelries', 'Sportwears', 'Wristwatches'];
            foreach($quickCategories as $cat):
            ?>
                <a href="shop.php?category=<?php echo urlencode($cat); ?>" 
                   class="px-3 py-1 bg-gray-200 rounded-full text-sm hover:bg-black hover:text-white transition">
                    <?php echo $cat; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </section>

    <!-- PRODUCTS -->
    <section class="max-w-7xl mx-auto px-6 pb-20">

        <?php if(count($products) > 0): ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php foreach($products as $p): ?>

            <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden group">

                <!-- Image -->
                <div class="relative overflow-hidden">

                    <?php 
                    // Add the correct image path
                    $imagePath = '../assets/images/' . $p['image'];
                    
                    // Check if image exists, if not use fallback
                    if (!file_exists($imagePath) || empty($p['image'])) {
                        $fallbackImages = [
                            'Fashion' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=300&fit=crop',
                            'Gadgets' => 'https://images.unsplash.com/photo-1589571894960-20bbe2828d0a?w=400&h=300&fit=crop',
                            'Jewelries' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=300&fit=crop',
                            'Sportwears' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400&h=300&fit=crop',
                            'Wristwatches' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=400&h=300&fit=crop'
                        ];
                        $imgSrc = $fallbackImages[$p['category']] ?? 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=400&h=300&fit=crop';
                    } else {
                        $imgSrc = '../assets/images/' . $p['image'];
                    }
                    ?>

                    <img
                        src="<?php echo htmlspecialchars($imgSrc); ?>"
                        class="w-full h-72 object-cover group-hover:scale-105 transition duration-500"
                        alt="<?php echo htmlspecialchars($p['name']); ?>"
                        onerror="this.src='https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=400&h=300&fit=crop'"
                    >

                    <!-- Category Badge -->
                    <span class="absolute top-4 left-4 bg-black text-white text-xs px-3 py-1 rounded-full">
                        <?php echo htmlspecialchars($p['category']); ?>
                    </span>

                </div>

                <!-- Content -->
                <div class="p-5">

                    <h3 class="text-lg font-semibold mb-2">
                        <?php echo htmlspecialchars($p['name']); ?>
                    </h3>

                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                        <?php 
                        // Use description if available, otherwise use a default
                        $description = !empty($p['description']) ? $p['description'] : 'Premium ' . $p['category'] . ' product with high quality materials.';
                        echo htmlspecialchars($description); 
                        ?>
                    </p>

                    <div class="flex items-center justify-between">

                        <span class="text-2xl font-bold">
                       ₦<?php echo number_format($p['price'], 2); ?>
                        </span>
                        <a
                            href="product.php?id=<?php echo $p['id']; ?>"
                            class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition"
                        >
                            View
                        </a>

                    </div>

                    <!-- Add To Cart -->
                   <form method="POST" action="add-to-cart.php" class="mt-4">
    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
    <button class="w-full bg-gray-100 hover:bg-black hover:text-white transition py-3 rounded-xl font-medium">
        Add To Cart
    </button>
</form>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <?php else: ?>

        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow p-16 text-center">

            <h2 class="text-3xl font-bold mb-4">
                No Products Found
            </h2>

            <p class="text-gray-500 mb-6">
                Try changing your search or filters.
            </p>

            <a
                href="shop.php"
                class="bg-black text-white px-6 py-3 rounded-xl hover:bg-gray-800"
            >
                Reset Filters
            </a>

        </div>

        <?php endif; ?>

    </section>

</div>

<?php include '../includes/footer.php'; ?>