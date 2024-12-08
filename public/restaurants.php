<?php
session_start();
include '../includes/db_connect.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM restaurants WHERE id = $id");
$restaurant = $result->fetch