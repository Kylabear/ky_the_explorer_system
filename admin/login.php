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
    <link href="../public/assets/tailwind.css" rel="stylesheet">
    <title>Admin Login</title>
</head>
<body class="bg-gradient-to-r from-purple-500 via-blue-500 to-green-500 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm p-8 space-y-6 bg-white bg-opacity-90 rounded-lg shadow-2xl transform transition-all duration-500 hover:scale-105">
        <h2 class="text-3xl font-semibold text-center text-gray-800 animate__animated animate__fadeInUp">Admin Login</h2>
        <form method="POST" class="space-y-4">
            <div class="animate__animated animate__fadeInUp animate__delay-1s">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" required>
            </div>
            <div class="animate__animated animate__fadeInUp animate__delay-2s">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" class="w-full p-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition duration-300 ease-in-out transform hover:scale-105" required>
            </div>
            <div>
                <button type="submit" class="w-full p-3 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                    Login
                </button>
            </div>
        </form>
        <p class="text-center text-sm text-gray-600 animate__animated animate__fadeInUp animate__delay-3s">
            Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>
