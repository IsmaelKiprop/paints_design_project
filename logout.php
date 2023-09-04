<?php
session_start();

// Clear all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other desired page
header('Location: index.php');
exit();
?>
