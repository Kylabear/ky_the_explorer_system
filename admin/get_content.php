<?php
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $table = isset($_GET['type']) && $_GET['type'] === 'cafe' ? 'cafes' : 'restaurants';

    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $content = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($content);
}
?>
