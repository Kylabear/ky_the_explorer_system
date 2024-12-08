<?php
// /includes/helpers.php

/**
 * Sanitize input data to prevent XSS attacks
 *
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a specified URL
 *
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Display a formatted error message
 *
 * @param string $message
 * @return string
 */
function displayError($message) {
    return "<div class='error-message'>{$message}</div>";
}

/**
 * Display a formatted success message
 *
 * @param string $message
 * @return string
 */
function displaySuccess($message) {
    return "<div class='success-message'>{$message}</div>";
}
?>
