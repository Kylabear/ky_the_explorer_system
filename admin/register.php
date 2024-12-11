<?php
session_start();
include '../includes/db_connect.php';
include '../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $email = sanitizeInput($_POST['email']);

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare('INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, ?)');
    $user_type = 'tourist'; // Default user type
    $stmt->bind_param('ssss', $username, $hashed_password, $email, $user_type);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user_type;
        header('Location: ../public/index.php');
        exit();
    } else {
        echo displayError('Registration failed');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-400 via-pink-300 to-pink-500">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-purple-600">Register</h2>
        <form action="register.php" method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="w-full p-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-purple-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            <button type="submit" class="w-full p-2 text-white bg-purple-600 rounded hover:bg-purple-700 transition duration-300">Register</button>
        </form>
        <a href="login.php" class="block text-center text-purple-600 hover:underline">Already have an account? Login</a>
    </div>
</body>
</html>
