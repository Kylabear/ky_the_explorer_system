<?php
session_start();
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM cafes WHERE id = $id");
    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc();
    } else {
        echo "Content not found!";
        exit;
    }
} else {
    echo "No ID specified!";
    exit;
}

// Check if form is submitted for updating content
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated content from the form
    $name = $_POST['name'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $features = $_POST['features'];
    $inclusions = $_POST['inclusions'];
    $drink_pricing = $_POST['drink_pricing'];
    $food_pricing = $_POST['food_pricing'];
    $ideal_for = $_POST['ideal_for'];
    
    // Update the content in the database
    $update_query = "UPDATE cafes SET
                        name = '$name',
                        location = '$location',
                        address = '$address',
                        features = '$features',
                        inclusions = '$inclusions',
                        drink_pricing = '$drink_pricing',
                        food_pricing = '$food_pricing',
                        ideal_for = '$ideal_for'
                    WHERE id = $id";

    if ($conn->query($update_query) === TRUE) {
        // Redirect back to the content list or a success page
        header("Location: cafes.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <link href="assets/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white text-gray-800 p-4 shadow-md">
        <nav class="container mx-auto flex justify-between space-x-4">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-pink-400 transition duration-300">Home</a></li>
                <li><a href="cafes.php" class="hover:text-pink-400 transition duration-300">Cafes</a></li>
                <li><a href="restaurants.php" class="hover:text-pink-400 transition duration-300">Restaurants</a></li>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-4">Edit Content</h1>
        <form action="edit_content.php?id=<?php echo $id; ?>" method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <!-- Cafe Name -->
            <div>
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($content['name']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-gray-700">Location</label>
                <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($content['location']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-gray-700">Address</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($content['address']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <!-- Features -->
            <div>
                <label for="features" class="block text-gray-700">Features</label>
                <textarea name="features" id="features" class="w-full p-2 border rounded-md"><?php echo htmlspecialchars($content['features']); ?></textarea>
            </div>

            <!-- Inclusions -->
            <div>
                <label for="inclusions" class="block text-gray-700">Inclusions</label>
                <textarea name="inclusions" id="inclusions" class="w-full p-2 border rounded-md"><?php echo htmlspecialchars($content['inclusions']); ?></textarea>
            </div>

            <!-- Drink Pricing -->
            <div>
                <label for="drink_pricing" class="block text-gray-700">Drink Pricing</label>
                <input type="text" name="drink_pricing" id="drink_pricing" value="<?php echo htmlspecialchars($content['drink_pricing']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <!-- Food Pricing -->
            <div>
                <label for="food_pricing" class="block text-gray-700">Food Pricing</label>
                <input type="text" name="food_pricing" id="food_pricing" value="<?php echo htmlspecialchars($content['food_pricing']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <!-- Ideal For -->
            <div>
                <label for="ideal_for" class="block text-gray-700">Ideal For</label>
                <input type="text" name="ideal_for" id="ideal_for" value="<?php echo htmlspecialchars($content['ideal_for']); ?>" class="w-full p-2 border rounded-md">
            </div>

            <div class="flex justify-end space-x-4">
                <a href="cafes.php" class="text-gray-600 hover:text-pink-400 transition duration-300">Cancel</a>
                <button type="submit" class="bg-pink-500 text-white py-2 px-4 rounded-md hover:bg-pink-600 transition duration-300">Update Content</button>
            </div>
        </form>
    </main>
</body>
</html>
