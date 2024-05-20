<?php
// Include your database configuration file (config.php)
include 'config.php';

// Initialize variables to store user inputs and error messages
$username = $email = $password = $countryCode = $phone = '';
$usernameErr = $emailErr = $passwordErr = $phoneErr = '';
$registrationSuccess = false;
$successMessage = '';

// Check if there's already an Owner in the database
$ownerExists = false;
$sql = "SELECT COUNT(*) AS ownerCount FROM users WHERE role = 'Owner'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row['ownerCount'] > 0) {
    $ownerExists = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize user inputs
    if (empty($_POST['username'])) {
        $usernameErr = 'Username is required.';
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
    }

    if (empty($_POST['email'])) {
        $emailErr = 'Email is required.';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = 'Invalid email format.';
        }
    }

    if (empty($_POST['password'])) {
        $passwordErr = 'Password is required.';
    } else {
        $password = $_POST['password'];
        // Check if the password is exactly 4 digits
        if (!preg_match('/^\d{4}$/', $password)) {
            $passwordErr = 'Password must be exactly 4 digits.';
        } else {
            // Hash the password using password_hash
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    if (empty($_POST['phone'])) {
        $phoneErr = 'Phone number is required.';
    } else {
        $countryCode = mysqli_real_escape_string($conn, $_POST['countryCode']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        // Validate phone number format
        if (!preg_match('/^\d{6,15}$/', $phone)) {
            $phoneErr = 'Invalid phone number format.';
        }
    }

    // Set the role based on the owner existence
    $role = 'Regular';
    if (!$ownerExists && isset($_POST['role']) && $_POST['role'] == 'Owner') {
        $role = 'Owner';
    }

    // Check if there are no input errors
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($phoneErr)) {
        // Insert user into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, country_code, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $countryCode, $phone, $role);

        if ($stmt->execute()) {
            $registrationSuccess = true;
            $successMessage = 'Registration successful! You will be redirected to the login page shortly.';
            // Redirect to the login page after a short delay
            header("refresh:3;url=login.php");
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register - Abdul Paints & Designs</title>
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
                var email = $("#email").val();
                var password = $("#password").val();
                var phone = $("#phone").val();

                if (username === "" || email === "" || password === "" || phone === "") {
                    alert("Please fill in all fields");
                    event.preventDefault();
                }

                // Check if the password is exactly 4 digits
                if (!/^\d{4}$/.test(password)) {
                    alert("Password must be exactly 4 digits.");
                    event.preventDefault();
                }

                // Check if the phone number format is valid
                if (!/^\d{6,15}$/.test(phone)) {
                    alert("Invalid phone number format.");
                    event.preventDefault();
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="registration-form">
            <h2 class="text-center mb-4">Create Your Account</h2>
            <?php if ($registrationSuccess): ?>
                <div class="alert alert-success">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="register.php">
                <div class="form-group">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <span class="error"><?php echo $usernameErr; ?></span> <!-- Display username error -->
                </div>
                <div class="form-group mt-3"> <!-- Add margin-top for spacing -->
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <span class="error"><?php echo $emailErr; ?></span> <!-- Display email error -->
                </div>
                <div class="form-group mt-3"> <!-- Add margin-top for spacing -->
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password (4 digits)" required>
                    <span class="error"><?php echo $passwordErr; ?></span> <!-- Display password error -->
                </div>
                <!-- Phone Number Field -->
                <div class="form-group mt-3"> <!-- Add margin-top for spacing -->
                    <label for="phone" class="mt-2">Phone Number:</label>
                    <div class="input-group">
                        <select name="countryCode" id="countryCode" class="form-control" required>
                            <option value="+1">+1 (USA)</option>
                            <option value="+44">+44 (UK)</option>
                            <option value="+91">+91 (India)</option>
                            <option value="+254">+254 (Kenya)</option>
                            <option value="+61">+61 (Australia)</option>
                            <option value="+81">+81 (Japan)</option>
                            <option value="+49">+49 (Germany)</option>
                            <option value="+33">+33 (France)</option>
                            <option value="+39">+39 (Italy)</option>
                            <option value="+86">+86 (China)</option>
                            <option value="+55">+55 (Brazil)</option>
                            <option value="+27">+27 (South Africa)</option>
                            <option value="+234">+234 (Nigeria)</option>
                            <option value="+7">+7 (Russia)</option>
                            <option value="+82">+82 (South Korea)</option>
                            <!-- Add more country codes as needed -->
                        </select>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>
                    <span class="error"><?php echo $phoneErr; ?></span> <!-- Display phone error -->
                </div>
                <!-- Role Selection Field -->
                <div class="form-group mt-3"> <!-- Add margin-top for spacing -->
                    <label for="role" class="mt-2">Select Role:</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="Regular">Regular</option>
                        <option value="Owner">Owner</option>
                        <!-- Add more options for other roles if needed -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-4">Register</button>
            </form>
            <p class="text-center mt-3" style="color: black;">Already registered? <a class="btn btn-primary" href="login.php" style="border: 2px solid #B78D65; background-color: #B78D65; color: white; text-decoration: none;">Log In</a></p>
        </div>
    </div>
</body>

</html>

<style>
    /* Center-align the registration form */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Adjust the height as needed */
        background: #f8f9fa; /* Light background color */
    }

    /* Add a frame around the registration form */
    .registration-form {
        border: 2px solid #B78D65; /* Border color */
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
    .error {
        color: #ff0000; /* Red color for errors */
        font-size: 0.875rem; /* Smaller font size */
    }

    /* Style the "Register" button */
    .btn-primary {
        background-color: #B78D65; /* Primary color */
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
        border: 2px solid #B78D65; /* Add a border */
    }

    /* Change the button background color on hover */
    .btn-primary:hover {
        background-color: #A57856; /* Darker shade on hover */
    }

    /* Style the "Log In" link */
    a.btn {
        display: block; /* Display as a block-level element */
        text-align: center; /* Center-align the text */
        margin-top: 15px; /* Top margin for spacing */
        text-decoration: none; /* Remove underlines */
        color: white; /* Initial color */
        transition: color 0.3s; /* Smooth color transition */
    }

    /* Change the link color on hover */
    a.btn:hover {
        color: white; /* Hover color */
    }

    /* Darker hover effect */
    a.btn.btn-primary:hover {
        background-color: #A57856;
    }
</style>
