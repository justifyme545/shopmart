<?php
require_once '../includes/auth.php';
guestOnly();

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-100 flex items-center justify-center px-6">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-10">
        
        <h1 class="text-4xl font-bold mb-2">Welcome Back</h1>
        <p class="text-gray-500 mb-8">Login to your account</p>
        
        <?php if($error): ?>
        <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6">
            <?php echo e($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="login-process.php">
            <div class="mb-5">
                <label class="block mb-2 font-medium">Email</label>
                <input type="email" name="email" required
                       class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-black outline-none">
            </div>
            
            <div class="mb-6">
                <label class="block mb-2 font-medium">Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-black outline-none">
            </div>
            
            <button class="w-full bg-black text-white py-4 rounded-xl hover:bg-gray-800 transition">
                Login
            </button>
        </form>
        
        <p class="text-center text-gray-500 mt-6">
            Don't have an account?
            <a href="register.php" class="text-black font-semibold">Register</a>
        </p>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>