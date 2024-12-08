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
    <link href="assets/tailwind.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($cafe['name']); ?></title>
</head>
<body>
    <header class="bg-white text-gray-800 p-4 shadow-md">
        <nav class="container mx-auto flex justify-between space-x-4">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-pink-400 transition duration-300">Home</a></li>
                <li><a href="cafes.php" class="hover:text-pink-400 transition duration-300">Cafes</a></li>
                <li><a href="restaurants.php" class="hover:text-pink-400 transition duration-300">Restaurants</a></li>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto p-4">
        <h1 class="text-4xl font-bold"><?php echo htmlspecialchars($cafe['name']); ?></h1>
        <table class="table-auto w-full mt-4">
            <tbody>
                <tr>
                    <td class="border px-4 py-2 font-bold">Location</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['location']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Address</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['address']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Features</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['features']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Inclusions</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['inclusions']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Drink Pricing</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['drink_pricing']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Food Pricing</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['food_pricing']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Ideal For</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['ideal_for']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Status</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['status']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-bold">Comments</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($cafe['comments']); ?></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
