<?php
// No session_start() here - auth.php already handles it
require_once '../includes/auth.php';
require_once '../includes/db.php';
include '../includes/header.php';

// Fetch all products
$stmt = query("SELECT * FROM products ORDER BY id DESC");
$products = all($stmt);
?>

<div class="px-10 py-10">

  <h1 class="text-3xl font-semibold mb-6">Featured Products</h1>

  <!-- CATEGORY FILTER -->
  <div class="flex gap-4 mb-6 flex-wrap">
    <a href="shop.php" 
       class="px-4 py-1 border rounded-full text-sm hover:bg-black hover:text-white transition">
      All
    </a>
    
    <?php
    $cats = ['Fashion','Gadgets','Jewelries','Sportwears','Wristwatches'];
    foreach($cats as $c):
    ?>
      <a href="shop.php?category=<?php echo urlencode($c); ?>" 
         class="px-4 py-1 border rounded-full text-sm hover:bg-black hover:text-white transition">
        <?php echo htmlspecialchars($c); ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- PRODUCTS GRID -->
  <div id="productGrid" class="grid md:grid-cols-4 gap-6">

    <?php foreach($products as $p): ?>
    <div class="productCard bg-white p-4 rounded-2xl shadow-sm"
         data-category="<?php echo htmlspecialchars($p['category']); ?>">

      <?php 
      $imagePath = '../assets/images/' . $p['image'];
      $fallbackImages = [
        'Fashion' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=300&fit=crop',
        'Gadgets' => 'https://images.unsplash.com/photo-1589571894960-20bbe2828d0a?w=400&h=300&fit=crop',
        'Jewelries' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=300&fit=crop',
        'Sportwears' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400&h=300&fit=crop',
        'Wristwatches' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=400&h=300&fit=crop'
      ];
      $fallbackImg = $fallbackImages[$p['category']] ?? 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=400&h=300&fit=crop';
      ?>
      
      <img src="<?php echo $imagePath; ?>"
           class="h-40 w-full object-cover rounded-lg"
           alt="<?php echo htmlspecialchars($p['name']); ?>"
           onerror="this.src='<?php echo $fallbackImg; ?>'">

      <h3 class="mt-2 font-medium"><?php echo htmlspecialchars($p['name']); ?></h3>
      <p class="text-gray-500 text-sm">₦<?php echo number_format($p['price'], 2); ?></p>

      <form method="POST" action="add-to-cart.php" class="mt-2">
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
        <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
          Add to Cart
        </button>
      </form>

    </div>
    <?php endforeach; ?>

  </div>

</div>

<?php include '../includes/footer.php'; ?>