<?php
session_start();
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = query("SELECT * FROM users WHERE email = ? AND role = 'admin'", [$email], "s");
    $admin = fetch($stmt);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_role'] = 'admin';
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid admin credentials';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold">Admin Login</h1>
                <p class="text-gray-600 mt-2">Access the admin dashboard</p>
            </div>
            
            <?php if($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required
                           class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                
                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800">
                    Login to Admin
                </button>
            </form>
        </div>
    </div>
</body>
</html>