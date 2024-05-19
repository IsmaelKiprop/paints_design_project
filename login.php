<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate and sanitize input data (consider using a validation library)
    $username = mysqli_real_escape_string($conn, $username);

    // Query the database for the user using prepared statements
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashedPassword, $user_role); // Include user_role here
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            // Set the user's session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_role'] = $user_role; // Set the user's role here

            // Redirect to the gallery page or another authorized page
            header('Location: gallery.php');
            exit();
        } else {
            $login_error = 'Incorrect username or password'; // Provide a user-friendly error message
        }
    } else {
        $login_error = 'User not found'; // Provide a user-friendly error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - Abdul Paints & Designs</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/icons/paintint-icon.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Teko:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Your Custom CSS Stylesheet -->
    <link href="css/styles.css" rel="stylesheet">

    <!-- Optional: Add a JavaScript library like jQuery for enhanced functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Example JavaScript for form validation
        $(document).ready(function() {
            $("form").submit(function(event) {
                var username = $("#username").val();
                var password = $("#password").val();

                if (username === "" || password === "") {
                    alert("Please fill in all fields");
                    event.preventDefault();
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="login-form">
            <h2 class="text-center mb-4">Welcome Back!</h2>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
            </form>
            <?php
            // Display login error message if it exists
            if (isset($login_error)) {
                echo '<p class="error-message mt-3 text-danger text-center">' . htmlspecialchars($login_error) . '</p>';
            }
            ?>
            <p class="text-center mt-3" style="color: black;">Don't have an account? <a class="btn btn-primary" href="register.php" style="border: 2px solid #B78D65; background-color: #B78D65; color: white; text-decoration: none;">Register here</a></p>
        </div>
    </div>
</body>

</html>

<style>
    /* Center-align the login form */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Adjust the height as needed */
        background: #f8f9fa; /* Light background color */
    }

    /* Add a frame around the login form */
    .login-form {
        border: 2px solid #B78D65; /* Blue border */
        padding: 30px; /* Padding */
        border-radius: 20px; /* Rounded corners */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); /* Shadow effect */
        width: 400px; /* Form width */
        background-color: #ffffff; /* White background */
    }

    /* Style the form labels */
    .form-group label {
        display: block; /* Display labels as blocks */
        margin-bottom: 5px; /* Space between labels and input fields */
        color: #333; /* Dark label color */
    }

    /* Style the error message */
    .error-message {
        font-size: 0.875rem; /* Smaller font size */
    }

    /* Style the "Register here" link */
    .register-link {
        display: block; /* Display as a block-level element */
        text-align: center; /* Center-align the text */
        margin-top: 15px; /* Top margin for spacing */
        text-decoration: none; /* Remove underlines */
        color: #B78D65; /* Initial color */
        transition: color 0.3s; /* Smooth color transition */
    }

    /* Change the link color on hover */
    .register-link:hover {
        color: #0056b3; /* Hover color */
    }

    /* Dark blue hover effect */
    a.btn.btn-primary:hover {
        background-color: #0056b3;
    }

    /* Adjust the button style */
    .btn-primary {
        background-color: #B78D65; /* Blue color */
        border: none; /* No border */
        padding: 10px 20px; /* Padding */
        cursor: pointer; /* Pointer cursor */
        border-radius: 5px; /* Rounded corners */
        transition: background-color 0.3s; /* Smooth transition */
    }
</style>
