<?php
// Sanitize input data
function sanitize($data) {
    global $mysqli;
    return $mysqli->real_escape_string(htmlspecialchars(trim($data)));
}

// Format price with currency
function formatPrice($price) {
    return '₱' . number_format($price, 2);
}

// Get category name by ID
function getCategoryName($categoryId) {
    global $mysqli;
    $stmt = executeQuery($mysqli, "SELECT category_name FROM category WHERE category_id = ?", [$categoryId], 'i');
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    return $category ? $category['category_name'] : 'Uncategorized';
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }
}
?>