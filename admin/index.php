<?php
session_start();
include '../includes/auth.php';
include '../includes/db_connect.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

// Add content logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_content'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $features = implode(', ', $_POST['features']);
    $inclusions = implode(', ', $_POST['inclusions']);
    $drink_pricing = $_POST['drink_pricing'];
    $food_pricing = $_POST['food_pricing'];
    $ideal_for = implode(', ', $_POST['ideal_for']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image = $image_path;
    } elseif (!empty($_POST['image_url'])) {
        $image = $_POST['image_url'];  // Using URL if provided
    }

    $table = $_POST['type'] === 'cafe' ? 'cafes' : 'restaurants';

    $stmt = $conn->prepare("INSERT INTO $table (name, description, location, address, features, inclusions, drink_pricing, food_pricing, ideal_for, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssssss', $name, $description, $location, $address, $features, $inclusions, $drink_pricing, $food_pricing, $ideal_for, $image);

    if ($stmt->execute()) {
        $success_message = "Successfully added $name to $table.";
    } else {
        $error_message = "Error: Could not add content.";
    }

    $stmt->close();
}

// Fetch existing content
$table = isset($_GET['type']) && $_GET['type'] === 'cafe' ? 'cafes' : 'restaurants';
$result = $conn->query("SELECT * FROM $table");

// Edit content logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_content'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $features = implode(', ', $_POST['features']);
    $inclusions = implode(', ', $_POST['inclusions']);
    $drink_pricing = $_POST['drink_pricing'];
    $food_pricing = $_POST['food_pricing'];
    $ideal_for = implode(', ', $_POST['ideal_for']);
    
    // Handle image update
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image = $image_path;
    } elseif (!empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }

    $stmt = $conn->prepare("UPDATE $table SET name = ?, description = ?, location = ?, address = ?, features = ?, inclusions = ?, drink_pricing = ?, food_pricing = ?, ideal_for = ?, image = ? WHERE id = ?");
    $stmt->bind_param('ssssssssssi', $name, $description, $location, $address, $features, $inclusions, $drink_pricing, $food_pricing, $ideal_for, $image, $id);

    if ($stmt->execute()) {
        $success_message = "Successfully updated $name.";
    } else {
        $error_message = "Error: Could not update content.";
    }

    $stmt->close();
}

// Delete content logic
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param('i', $delete_id);
    if ($stmt->execute()) {
        header("Location: index.php?type=" . $_GET['type']);
    } else {
        $error_message = "Error: Could not delete content.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Dashboard</title>
</head>
<body class="bg-gradient-to-br from-pink-200 via-purple-200 to-blue-300 min-h-screen flex flex-col">
    <!-- Header Section -->
    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto text-center">
            <h1 class="text-3xl font-bold text-gray-800">Welcome to Admin Dashboard</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto flex-grow flex flex-col md:flex-row mt-8">
        <!-- Sidebar -->
        <nav class="bg-white shadow-lg rounded-lg p-4 w-full md:w-1/4 mb-6 md:mb-0">
            <ul class="space-y-4">
                <li><a href="?type=cafe" class="block bg-pink-400 text-white py-2 px-4 rounded-lg text-center hover:bg-pink-500 transition duration-300">Manage Cafes</a></li>
                <li><a href="?type=restaurant" class="block bg-purple-400 text-white py-2 px-4 rounded-lg text-center hover:bg-purple-500 transition duration-300">Manage Restaurants</a></li>
                <li><a href="logout.php" class="block bg-red-400 text-white py-2 px-4 rounded-lg text-center hover:bg-red-500 transition duration-300">Logout</a></li>
            </ul>
        </nav>

        <!-- Content Area -->
        <section class="flex-grow bg-white shadow-lg rounded-lg p-8">
            <?php if (isset($_GET['type'])): ?>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Manage <?= ucfirst($_GET['type']) ?></h2>
                <button onclick="document.getElementById('addContentForm').classList.toggle('hidden')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Content</button>

                <!-- Add Content Form -->
                <form id="addContentForm" class="mt-4 hidden" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="<?= htmlspecialchars($_GET['type']) ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Name" class="border rounded p-2" required>
                        <textarea name="description" placeholder="Brief Description" class="border rounded p-2" required></textarea>
                        <input type="text" name="location" placeholder="Location" class="border rounded p-2" required>
                        <input type="text" name="address" placeholder="Address" class="border rounded p-2" required>
                        
                        <!-- Features and Inclusions -->
                        <fieldset>
                            <legend>Features:</legend>
                            <label><input type="checkbox" name="features[]" value="Indoor Seating"> Indoor Seating</label>
                            <label><input type="checkbox" name="features[]" value="Outdoor Seating"> Outdoor Seating</label>
                            <label><input type="checkbox" name="features[]" value="Instagramable Decor"> Instagramable Decor</label>
                            <label><input type="checkbox" name="features[]" value="Rooftop Seating"> Rooftop Seating</label>
                            <label><input type="checkbox" name="features[]" value="Themed Decor"> Themed Decor</label>
                        </fieldset>
                        <fieldset>
                            <legend>Inclusions:</legend>
                            <label><input type="checkbox" name="inclusions[]" value="Charging Port"> Charging Port</label>
                            <label><input type="checkbox" name="inclusions[]" value="WiFi"> WiFi</label>
                        </fieldset>
                        
                        <input type="text" name="drink_pricing" placeholder="Drink Pricing" class="border rounded p-2" required>
                        <input type="text" name="food_pricing" placeholder="Food Pricing" class="border rounded p-2" required>
                        
                        <fieldset>
                            <legend>Ideal For:</legend>
                            <label><input type="checkbox" name="ideal_for[]" value="Student"> Student</label>
                            <label><input type="checkbox" name="ideal_for[]" value="Work Friendly"> Work Friendly</label>
                        </fieldset>

                        <!-- Image Upload or URL -->
                        <div>
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" id="image" class="border rounded p-2">
                            <small>OR</small>
                            <input type="text" name="image_url" placeholder="Image URL" class="border rounded p-2">
                        </div>
                    </div>
                    <button type="submit" name="add_content" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
                </form>

                <?php if (isset($success_message)): ?>
                    <div class="mt-4 bg-green-100 text-green-700 p-2 rounded"> <?= $success_message ?> </div>
                <?php elseif (isset($error_message)): ?>
                    <div class="mt-4 bg-red-100 text-red-700 p-2 rounded"> <?= $error_message ?> </div>
                <?php endif; ?>

                <!-- Display Existing Content -->
                <h3 class="text-lg font-semibold text-gray-700 mt-8">Existing <?= ucfirst($_GET['type']) ?></h3>
                <table class="table-auto w-full mt-4 border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-2">Name</th>
                            <th class="border p-2">Description</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border p-2"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="border p-2"><?= htmlspecialchars($row['description']) ?></td>
                                <td class="border p-2">
                                    <!-- Edit Button -->
                                    <button onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars($row['description']) ?>')" class="bg-yellow-500 text-white py-1 px-4 rounded hover:bg-yellow-600">Edit</button>

                                    <!-- Delete Button -->
                                    <button onclick="openDeleteModal(<?= $row['id'] ?>)" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h2 class="text-xl font-semibold text-gray-700">Please select an option from the sidebar.</h2>
            <?php endif; ?>
        </section>
    </main>

    <!-- Edit Content Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center flex items-center">
        <div class="bg-white p-4 rounded-lg max-w-xl">
            <h2 class="text-xl font-semibold">Edit Content</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" id="editName" placeholder="Name" class="border rounded p-2" required>
                    <textarea name="description" id="editDescription" placeholder="Description" class="border rounded p-2" required></textarea>
                    <input type="text" name="location" id="editLocation" placeholder="Location" class="border rounded p-2" required>
                    <input type="text" name="address" id="editAddress" placeholder="Address" class="border rounded p-2" required>
                </div>
                <div class="mt-4">
                    <label for="editImage">Upload Image</label>
                    <input type="file" name="image" id="editImage" class="border rounded p-2">
                </div>
                <button type="submit" name="edit_content" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
            </form>
            <button onclick="closeModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center flex items-center">
        <div class="bg-white p-4 rounded-lg">
            <h2 class="text-lg font-semibold">Are you sure you want to delete this content?</h2>
            <div class="mt-4">
                <button id="deleteConfirm" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Yes</button>
                <button onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">No</button>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, description) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteConfirm').onclick = function() {
                window.location.href = '?delete_id=' + id;
            };
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>
</html>
