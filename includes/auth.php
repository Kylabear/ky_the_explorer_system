<?php

session_start();

function isLoggedIn() {
    return isset($_SESSION['username']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_type'] === 'admin';
}
?>

