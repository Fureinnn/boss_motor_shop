<?php
// Database configuration
$host = 'localhost';
$dbname = 'boss_motor_shop';
$username = 'root';
$password = '';

// Create MySQLi connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8mb4 for proper encoding
$mysqli->set_charset("utf8mb4");

// Function to execute prepared queries with error handling
function executeQuery($mysqli, $sql, $params = [], $types = '') {
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        // Check for foreign key constraint failure
        if ($stmt->errno == 1452) {
            die("Database integrity error: Referenced record doesn't exist");
        }
        die("Execute failed: " . $stmt->error);
    }
    
    return $stmt;
}

// Verify user exists function
function verifyUserExists($userId) {
    global $mysqli;
    $stmt = executeQuery($mysqli, "SELECT user_id FROM user WHERE user_id = ?", [$userId], 'i');
    return $stmt->get_result()->num_rows > 0;
}
?>