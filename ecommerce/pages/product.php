<?php
require_once '../includes/db.php';
include '../includes/header.php';

// Get product ID
$id = $_GET['id'] ?? 0;

// Fetch product
$stmt = query("SELECT * FROM products WHERE id = ?", [$id], "i");
$product = fetch($stmt);

if (!$product) {
    die("Product not found");
}

// Fetch related products (same category)
$stmt2 = query(
    "SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4",
    [$product['category'], $id],
    "si"
);
$related = all($stmt2);  // FIXED: Changed from fetchAll to all
?>

<div class="px-10 py-10">

  <!-- PRODUCT SECTION -->
  <div class="grid md:grid-cols-2 gap-10">

    <!-- IMAGE -->
    <div>
      <img src="../assets/images/<?php echo $product['image']; ?>"
           class="w-full h-[400px] object-cover rounded-2xl shadow-sm"
           onerror="this.src='https://via.placeholder.com/400'">
    </div>

    <!-- DETAILS -->
    <div>

      <h1 class="text-2xl font-semibold">
        <?php echo e($product['name']); ?>
      </h1>

      <p class="text-gray-500 mt-2">
        Category: <?php echo e($product['category']); ?>
      </p>

      <p class="text-2xl mt-4 font-bold">
        $<?php echo number_format($product['price'], 2); ?>
      </p>

      <p class="mt-4 text-gray-600">
        High-quality product designed for comfort and durability.
      </p>

      <!-- ADD TO CART -->
      <form method="POST" action="cart.php" class="mt-6">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <button class="bg-black text-white px-6 py-3 rounded-lg w-full hover:bg-gray-800">
          Add to Cart
        </button>
      </form>

    </div>
  </div>

  <!-- RELATED PRODUCTS -->
  <div class="mt-16">

    <h2 class="text-xl font-semibold mb-6">Related Products</h2>

    <div class="grid md:grid-cols-4 gap-6">

      <?php foreach($related as $p): ?>
      <a href="product.php?id=<?php echo $p['id']; ?>">

        <div class="bg-white p-4 rounded-xl shadow-sm hover:shadow-md">

          <img src="../assets/images/<?php echo $p['image']; ?>"
               class="h-40 w-full object-cover rounded-lg"
               onerror="this.src='https://via.placeholder.com/160'">

          <h3 class="mt-2 text-sm"><?php echo e($p['name']); ?></h3>
         <p class="text-2xl mt-4 font-bold">
    ₦<?php echo number_format($product['price'], 2); ?>
</p>

        </div>

      </a>
      <?php endforeach; ?>

    </div>

  </div>

</div>

<?php include '../includes/footer.php'; ?>