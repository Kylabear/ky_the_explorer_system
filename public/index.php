<?php
session_start();
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/tailwind.css" rel="stylesheet">
    <title>Ky the Explorer</title>
</head>
<body class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500">
    <header class="bg-white text-gray-800 p-4 shadow-md">
        <nav class="container mx-auto flex justify-between space-x-4">
            <ul class="flex flex-col md:flex-row md:space-x-4">
                <li><a href="#" class="hover:text-pink-400 transition duration-300">Home</a></li>
                <li><a href="#" class="hover:text-pink-400 transition duration-300">About</a></li>
                <li><a href="#" class="hover:text-pink-400 transition duration-300">Contact</a></li>
            </ul>
            <ul class="flex flex-col md:flex-row md:space-x-4 justify-end">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="self-end md:self-auto text-pink-500 font-bold">
                        Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!
                    </li>
                    <li class="self-end md:self-auto">
                        <a href="../admin/logout.php" class="hover:text-pink-400 transition duration-300">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="self-end md:self-auto">
                        <a href="../admin/login.php" class="hover:text-pink-400 transition duration-300">Login/Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto p-4">
        <section class="intro text-center mt-8">
            <h1 class="text-4xl font-bold text-white">Welcome to Ky the Explorer</h1>
            <p class="text-lg text-white mt-4">Discover the best cafes and restaurants in Baguio City.</p>
            <a href="#cafes" class="cta-button bg-white text-pink-500 py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-300 mt-4 inline-block">View Cafes</a>
            <a href="#restaurants" class="cta-button bg-white text-pink-500 py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-300 mt-4 inline-block">View Restaurants</a>
        </section>

        <!-- Cafes Section -->
        <section id="cafes" class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-white mb-4">Top Cafes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $result = $conn->query("SELECT * FROM cafes");
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="bg-white rounded-lg shadow-lg p-6 group">';
                    echo '<h3 class="text-xl font-bold text-gray-800">' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p class="text-gray-600">' . htmlspecialchars($row['location']) . '</p>';
                    echo '<p class="text-gray-600">Rating: ★★★★☆</p>';
                    echo '<a href="cafes.php?id=' . $row['id'] . '" class="bg-green-600 text-white px-4 py-2 rounded mt-4 inline-block hover:bg-green-700">See More</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <!-- Restaurants Section -->
        <section id="restaurants" class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-white mb-4">Top Restaurants</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $result = $conn->query("SELECT * FROM restaurants");
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="bg-white rounded-lg shadow-lg p-6 group">';
                    echo '<h3 class="text-xl font-bold text-gray-800">' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p class="text-gray-600">' . htmlspecialchars($row['location']) . '</p>';
                    echo '<p class="text-gray-600">Rating: ★★★★☆</p>';
                    echo '<a href="restaurants.php?id=' . $row['id'] . '" class="bg-green-600 text-white px-4 py-2 rounded mt-4 inline-block hover:bg-green-700">See More</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>
