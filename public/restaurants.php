<?php
session_start();
include '../includes/db_connect.php';

// Get the restaurant ID from the URL
$id = intval($_GET['id']); // Ensure the ID is an integer to prevent SQL injection

// Query the database for the restaurant with the given ID
$result = $conn->query("SELECT * FROM restaurants WHERE id = $id");

// Check if the query succeeded
if ($result && $result->num_rows > 0) {
    // Fetch the restaurant data as an associative array
    $restaurant = $result->fetch_assoc();
} else {
    // Handle the case where no restaurant is found
    echo "<div style='text-align: center; margin-top: 50px;'><h1>No restaurant found with the given ID.</h1></div>";
    exit; // Stop further execution
}

// Handle adding comments
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = htmlspecialchars($_POST['comment']);
    $rating = intval($_POST['rating']);
    $stmt = $conn->prepare("INSERT INTO restaurant_reviews (restaurant_id, comment, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id, $comment, $rating);
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!');</script>";
    } else {
        echo "<script>alert('Error submitting your feedback.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .restaurant-image {
            text-align: center;
            margin-bottom: 20px;
        }
        .restaurant-image img {
            max-width: 100%;
            border-radius: 8px;
        }
        .restaurant-details p {
            margin: 10px 0;
            color: #555;
        }
        .form-container {
            margin-top: 30px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .form-container button {
            padding: 10px 15px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Restaurant Details</h1>
        
        <!-- Restaurant Image -->
        <div class="restaurant-image">
            <?php if (!empty($restaurant['image'])): ?>
                <img src="<?= htmlspecialchars($restaurant['image']); ?>" alt="Restaurant Image">
            <?php else: ?>
                <p>No image available.</p>
            <?php endif; ?>
        </div>

        <!-- Restaurant Details -->
        <div class="restaurant-details">
            <p><strong>Name:</strong> <?= htmlspecialchars($restaurant['name']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($restaurant['description']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($restaurant['location']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($restaurant['address']); ?></p>
            <p><strong>Features:</strong> <?= htmlspecialchars($restaurant['features']); ?></p>
            <p><strong>Inclusions:</strong> <?= htmlspecialchars($restaurant['inclusions']); ?></p>
            <p><strong>Drink Pricing:</strong> <?= htmlspecialchars($restaurant['drink_pricing']); ?></p>
            <p><strong>Food Pricing:</strong> <?= htmlspecialchars($restaurant['food_pricing']); ?></p>
            <p><strong>Ideal For:</strong> <?= htmlspecialchars($restaurant['ideal_for']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($restaurant['status']); ?></p>
            <p><strong>Comments:</strong> <?= htmlspecialchars($restaurant['comments']); ?></p>
        </div>

        <!-- Comment and Rating Form -->
        <div class="form-container">
            <h2>Leave a Comment</h2>
            <form method="POST">
                <textarea name="comment" placeholder="Write your comment here..." required></textarea>
                <label for="rating">Rating:</label>
                <select name="rating" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    </div>
</body>
</html>
