<?php
// Check if the success parameter is set in the URL
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    // Display success message
    echo "<p>Appointment sent successfully! We will contact you shortly.</p>";
} else {
    // Display a generic message
    echo "<p>Thank you for your submission.</p>";
}
?>