<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$serviceNames = [
    '1' => 'Gypsum',
    '2' => 'Painting',
    '3' => 'Terrazzo',
    '4' => 'Fitting',
    '5' => 'Cabroids',
    '6' => 'Ruff & Tuff'
];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $selectedServiceCode = $_POST["service"];
    $selectedServiceName = isset($serviceNames[$selectedServiceCode]) ? $serviceNames[$selectedServiceCode] : 'Unknown';
    $appointmentDate = $_POST["date"];
    $appointmentTime = $_POST["time"];
    $message = $_POST["message"];

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings (Use Gmail for this example)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kipropismael27@gmail.com';
        $mail->Password = 'ieeaqolgwbolaqvk';
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption (or 'ssl' for SSL encryption)
        $mail->Port = 587; // Use 587 for TLS or 465 for SSL

        // Sender and recipient
        $mail->setFrom($email, $name); // Use the submitted email and name as the sender
        $mail->addAddress('kipropismael27@gmail.com', 'Ismael'); // Replace with the recipient's email and name

        // Email content
        $mail->isHTML(false); // Set to true if your email content is HTML
        $mail->Subject = 'New Appointment Request';
        $mail->Body = "Name: $name\nEmail: $email\nMobile: $mobile\nService: $selectedServiceName\nDate: $appointmentDate\nTime: $appointmentTime\nMessage: $message";

        // Send the email
        $mail->send();

        // Email sent successfully
        header("Location: thank_you.php");
        exit();
    } catch (Exception $e) {
        // Email sending failed
        echo "Email sending failed. Please try again later. Error: " . $mail->ErrorInfo;
    }
}
?>
