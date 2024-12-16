<?php
session_start();
include '../includes/db_connect.php';

// Get the cafe ID from the URL
$id = intval($_GET['id']); // Ensure the ID is an integer to prevent SQL injection

// Query the database for the cafe with the given ID
$result = $conn->query("SELECT * FROM cafes WHERE id = $id");

// Check if the query succeeded
if ($result && $result->num_rows > 0) {
    // Fetch the cafe data as an associative array
    $cafe = $result->fetch_assoc();
} else {
    echo "<div style='text-align: center; margin-top: 50px;'><h1>No cafe found with the given ID.</h1></div>";
    exit; // Stop further execution
}

// Handle adding comments
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = htmlspecialchars($_POST['comment']);
    $rating = intval($_POST['rating']);
    $stmt = $conn->prepare("INSERT INTO cafe_reviews (cafe_id, comment, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id, $comment, $rating);
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!');</script>";
    } else {
        echo "<script>alert('Error submitting your feedback.');</script>";
    }
}

// Fetch comments and ratings for the cafe
$reviews = $conn->query("SELECT * FROM cafe_reviews WHERE cafe_id = $id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo htmlspecialchars($cafe['name']); ?></title>
</head>
<body class="bg-gradient-to-br from-purple-400 via-lavender-300 to-pink-500 min-h-screen flex justify-center items-center">

    <!-- Main Container -->
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl w-full">
        <!-- Cafe Image -->
        <div class="mb-6 text-center">
            <?php if (!empty($cafe['image'])): ?>
                <img src="<?php echo htmlspecialchars($cafe['image']); ?>" alt="<?php echo htmlspecialchars($cafe['name']); ?>" class="rounded-lg shadow-md mx-auto max-h-64">
            <?php else: ?>
                <p class="text-gray-500">No image available.</p>
            <?php endif; ?>
        </div>

        <!-- Cafe Details -->
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-4"><?php echo htmlspecialchars($cafe['name']); ?></h1>
        <table class="table-auto w-full mb-6 text-sm text-gray-700">
            <tbody>
                <tr>
                    <td class="py-2 px-4 font-bold">Location</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['location']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Address</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['address']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Features</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['features']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Inclusions</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['inclusions']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Drink Pricing</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['drink_pricing']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Food Pricing</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['food_pricing']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Ideal For</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['ideal_for']); ?></td>
                </tr>
                <tr>
                    <td class="py-2 px-4 font-bold">Status</td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($cafe['status']); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Comment and Rating Form -->
        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 text-center mb-4">Leave a Comment</h2>
            <form method="POST" class="space-y-4">
                <textarea name="comment" class="w-full p-3 border rounded-md" placeholder="Write your comment here..." required></textarea>
                <select name="rating" class="w-full p-3 border rounded-md" required>
                    <option value="">Select Rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
                <button type="submit" class="w-full bg-pink-500 text-white py-2 px-4 rounded-md hover:bg-pink-600 transition duration-300">Submit</button>
            </form>
        </div>

        <!-- Display Comments and Ratings -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold text-gray-700 text-center mb-4">User Comments and Ratings</h2>
            <?php if ($reviews && $reviews->num_rows > 0): ?>
                <div class="space-y-4">
                    <?php while ($review = $reviews->fetch_assoc()): ?>
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <p class="text-gray-800"><strong>Rating:</strong> <?php echo $review['rating']; ?>/5</p>
                            <p class="text-gray-600"><strong>Comment:</strong> <?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center">No reviews yet. Be the first to leave a comment!</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
