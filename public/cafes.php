<?php
session_start();
include '../includes/db_connect.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM cafes WHERE id = $id");
$cafe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($cafe['name']); ?></title>
</head>
<body class="bg-gradient-to-br from-purple-400 via-lavender-300 to-pink-500 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white text-gray-800 p-4 shadow-md">
        <nav class="container mx-auto flex justify-between items-center space-x-4">
            <ul class="flex flex-col md:flex-row md:space-x-4">
                <li><a href="index.php" class="hover:text-pink-400 transition duration-300">Home</a></li>
                <li><a href="cafes.php" class="hover:text-pink-400 transition duration-300">Cafes</a></li>
                <li><a href="restaurants.php" class="hover:text-pink-400 transition duration-300">Restaurants</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content -->
    <main class="flex-grow container mx-auto p-4">

        <!-- Cafe Details -->
        <section class="mt-8 text-center">
            <h1 class="text-4xl font-bold text-white animate__animated animate__fadeIn"><?php echo htmlspecialchars($cafe['name']); ?></h1>
            <table class="table-auto w-full mt-4 bg-white shadow-md rounded-lg">
                <tbody>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Location</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['location']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Address</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['address']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Features</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['features']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Inclusions</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['inclusions']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Drink Pricing</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['drink_pricing']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Food Pricing</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['food_pricing']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Ideal For</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['ideal_for']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Status</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['status']); ?></td>
                    </tr>
                    <tr class="hover:bg-gray-100 transition duration-300">
                        <td class="border px-4 py-2 font-bold">Comments</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['comments']); ?></td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-600 p-4 text-center shadow-inner mt-8">
        <p>&copy; 2024 Ky the Explorer. All rights reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>

</body>
</html>
