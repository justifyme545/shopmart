<?php
require_once '../includes/auth.php'; // This starts session
require_once '../includes/db.php';
include '../includes/header.php';

// Debug - verify session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart from GET (if coming from product page)
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: cart.php');
    exit();
}

// Update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = (int)$qty;
        }
    }
    header('Location: cart.php');
    exit();
}

// Remove item
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header('Location: cart.php');
    exit();
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit();
}

// Fetch products
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = query("SELECT * FROM products WHERE id = ?", [$id], "i");
        $product = fetch($stmt);
        if ($product) {
            $product['qty'] = $qty;
            $product['subtotal'] = $product['price'] * $qty;
            $total += $product['subtotal'];
            $cartItems[] = $product;
        }
    }
}
?>

<div class="px-10 py-10">

<h1 class="text-2xl font-semibold mb-6">Shopping Cart</h1>

<?php if (!empty($cartItems)): ?>

<form method="POST">
<div class="grid md:grid-cols-3 gap-8">

<!-- CART ITEMS -->
<div class="md:col-span-2 space-y-4">

<?php foreach ($cartItems as $item): ?>
<div class="bg-white p-4 rounded-2xl shadow-sm flex gap-4 items-center">

<img src="../assets/images/<?php echo $item['image']; ?>"
     class="w-24 h-24 object-cover rounded-lg"
     onerror="this.src='https://via.placeholder.com/96'">

<div class="flex-1">
  <h2 class="font-medium"><?php echo e($item['name']); ?></h2>
  <p class="text-gray-500 text-sm">₦<?php echo number_format($item['price'], 2); ?></p>

  <input type="number" min="1"
         name="qty[<?php echo $item['id']; ?>]"
         value="<?php echo $item['qty']; ?>"
         class="border w-20 mt-2 p-1 rounded">
</div>

<div class="text-right">
  <p class="font-semibold">₦<?php echo number_format($item['subtotal'], 2); ?></p>
  <a href="?remove=<?php echo $item['id']; ?>"
     class="text-red-500 text-sm mt-2 inline-block">
     Remove
  </a>
</div>

</div>
<?php endforeach; ?>

<div class="flex gap-2">
    <button name="update" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
        Update Cart
    </button>
    <a href="?clear=1" class="bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200"
       onclick="return confirm('Clear entire cart?')">
        Clear Cart
    </a>
</div>

</div>

<!-- SUMMARY -->
<div class="bg-white p-6 rounded-2xl shadow-sm h-fit">

<h2 class="text-lg font-semibold mb-4">Order Summary</h2>

<div class="flex justify-between mb-2">
  <span>Subtotal</span>
  <span>₦<?php echo number_format($total, 2); ?></span>
</div>

<div class="flex justify-between mb-2">
  <span>Shipping</span>
  <span>₦5,000.00</span>
</div>

<hr class="my-3">

<div class="flex justify-between font-semibold text-lg">
  <span>Total</span>
  <span>₦<?php echo number_format($total + 5000, 2); ?></span>
</div>

<a href="checkout.php"
   class="block text-center mt-4 bg-black text-white py-3 rounded-lg hover:bg-gray-800">
   Proceed to Checkout
</a>

</div>

</div>
</form>

<?php else: ?>

<div class="text-center py-20">
  <p class="text-gray-500">Your cart is empty</p>
  <a href="index.php" class="mt-4 inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
     Continue Shopping
  </a>
</div>

<?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>