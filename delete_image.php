<?php
// delete_image.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// Check if the user is logged in and has the necessary permissions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageId = $_POST["id"];
    
    // Fetch the image information from the database
    $sql = "SELECT file_name, category FROM image WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $imageId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $fileName = $row['file_name'];
        $category = $row['category'];
        
        // Construct the image path
        $imagePath = "uploads/$category/$fileName";
        
        // Check if the file exists before attempting to delete
        if (file_exists($imagePath)) {
            // Delete the file from the server
            if (unlink($imagePath)) {
                // Delete the image record from the database
                $sql = "DELETE FROM image WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $imageId);
                if (mysqli_stmt_execute($stmt)) {
                    // Echo the success message with inline CSS for styling
                    echo '<div style="text-align: center; margin-top: 20px; font-size: 18px; color: green; font-weight: bold;">';
                    echo '✔ Image deleted successfully';
                    echo '</div>';

                    // Redirect back to gallery.php after a short delay
                    echo '<script>';
                    echo 'setTimeout(function() { window.location.href = "gallery.php"; }, 2000);'; // Redirect after 2 seconds
                    echo '</script>';
                    
                    exit();
                } else {
                    // Handle database error
                    echo 'Error: Database error';
                }
            } else {
                // Handle file deletion error
                echo 'Error: File deletion error';
            }
        } else {
            // Handle file not found error
            echo 'Error: File not found';
        }
    } else {
        // Handle image not found in the database
        echo 'Error: Image not found in the database';
    }
}

// Handle file deletion error
echo 'Error: ' . error_get_last()['message'];
?>
