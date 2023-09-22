<?php
// Start the session at the very beginning
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$dbHost = 'localhost'; // Assuming the database is hosted locally
$dbUser = 'root';      // Default MySQL username
$dbPass = '';           // Default MySQL password (empty string)
$dbName = 'myproject'; // Your database name

// Create a database connection
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


