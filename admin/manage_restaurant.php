<?php
include '../includes/auth.php';
include '../includes/db_connect.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/assets/tailwind.css" rel="stylesheet">
    <title>Manage Restaurants</title>
</head>
<body>
    <header>
        <h1>Manage Restaurants</h1>
        <nav>
            <a href="index.php">Dashboard</a> |
            <a href="manage_cafes.php">Manage Cafes</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>
</body>
</html>
