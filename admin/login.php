<?php
session_start();
include '../includes/db_connect.php';
include '../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    $stmt = $conn->prepare('SELECT id, username, password, user_type FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $user_type);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;

            // Updated redirection logic based on user type
            if ($user_type === 'admin') {
                header('Location: /ky_the_explorer_system/admin/index.php'); // Ensure full path
            } else {
                header('Location: /ky_the_explorer_system/public/index.php'); // Redirect to main page for regular users
            }
            exit();
        } else {
            echo displayError('Invalid username or password');
        }
    } else {
        echo displayError('Invalid username or password');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Login</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-400 via-pink-300 to-pink-500">

    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-purple-600">Login</h2>
        <form method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="w-full p-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            <button type="submit" class="w-full p-2 text-white bg-purple-600 rounded hover:bg-purple-700 transition duration-300">Login</button>
        </form>
        <p class="text-center text-sm text-gray-600">
            Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>
