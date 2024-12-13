<?php
session_start();
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_query = "DELETE FROM cafes WHERE id = ?";

    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: cafes.php");
            exit();
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
} else {
    echo "No ID specified!";
    exit;
}

$conn->close();
?>
