<?php
// Assuming you have started the session already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is a regular user, redirect to gallery_without_upload.php
if ($_SESSION['user_role'] === 'Regular') {
    header('Location: gallery_without_upload.php');
    exit();
}

// Check for POST method to handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = isset($_POST["category"]) ? $_POST["category"] : '';

    if (!empty($_FILES["image"]["name"])) {
        // Directory where the uploaded images will be stored
        $targetDirectory = "uploads/$category/";

        // Create the directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        $fileName = $_FILES["image"]["name"];
        $targetFile = $targetDirectory . $fileName;

        // Check if the image file is valid
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowedExtensions)) {
            // Generate a unique filename to avoid overwriting existing files
            $uniqueFileName = uniqid('', true) . '.' . $imageFileType;
            $targetFile = $targetDirectory . $uniqueFileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Use prepared statement to prevent SQL injection
                $sql = "INSERT INTO image (file_name, category, uploaded_by) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);

                // Get the user_id from the session
                $user_id = $_SESSION['user_id'];

                mysqli_stmt_bind_param($stmt, "sss", $uniqueFileName, $category, $user_id);

                if (mysqli_stmt_execute($stmt)) {
                    echo "Image uploaded successfully!";
                } else {
                    echo "Error: " . mysqli_stmt_error($stmt);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        echo "Please select an image to upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Abdul Paints & Designs</title>
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

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>


    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="file:///C:/Users/ISMAEL/OneDrive/Desktop/my%20portfolio/paints_designs_web/lightbox/css/lightbox.css">
    <script src="file:///C:/Users/ISMAEL/OneDrive/Desktop/my%20portfolio/paints_designs_web/lightbox/js/lightbox.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</head>
<body>

      <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border position-relative text-primary" style="width: 6rem; height: 6rem;" role="status"></div>
        <img class="position-absolute top-50 start-50 translate-middle" src="img/icons/paintint-icon.png" alt="Icon">
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-dark p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-3">
                    <a class="text-body px-2" href="tel:+254 718 893 450"><i class="fa fa-phone-alt text-primary me-2"></i>+254 718 893 450</a>
                    <a class="text-body px-2" href="mailto:abdulpaintsdesigns45@gmail.com"><i class="fa fa-envelope-open text-primary me-2"></i>abdulpaintsdesigns45@gmail.com</a>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center">
                    <a class="btn btn-sm-square btn-outline-body me-1" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm-square btn-outline-body me-1" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-sm-square btn-outline-body me-1" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm-square btn-outline-body me-0" href=""><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="index.php" class="navbar-brand ms-4 ms-lg-0">
            <h1 class="text-primary m-0"><img class="me-3" src="img/icons/paintint-icon.png" alt="Icon">AbdulPaints&Designs</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="service.php" class="nav-item nav-link">Services</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu border-0 m-0">
                        <a href="feature.php" class="dropdown-item">Our Features</a>
                        <a href="project.php" class="dropdown-item">Our Projects</a>
                        <a href="team.php" class="dropdown-item">Team Members</a>
                        <a href="appointment.php" class="dropdown-item">Appointment</a>
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                        <a href="gallery.php" class="dropdown-item">Gallary</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link active">Contact</a>
            </div>
            <a href="appointment.php" class="btn btn-primary py-2 px-4 d-none d-lg-block">Appointment</a>
        </div>
    </nav>

    <?php
    // Display success message if it's set
    if (isset($_GET['success'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
    }
    ?>

    <div id="successMessage" class="alert-message"></div>

        <div class="upload-logout-container">
    <div class="upload-form">
        <h2>Upload Images</h2>
        <form method="post" action="gallery.php" enctype="multipart/form-data">
            <label for="category">Select Category:</label>
            <select name="category" id="category" required>
                <option value="gypsum">Gypsum</option>
                <option value="painting">Painting</option>
                <option value="fitting">Fitting</option>
                <option value="terrazzo">Terrazzo</option>
                <option value="cabroids">Cabroids</option>
                <!-- Add more options for other categories -->
            </select><br>
            <input type="file" name="image" required><br>
            <input type="submit" value="Upload Image">
        </form>
    </div>

    <div class="logout-form">
        <form method="post" action="logout.php">
            <input type="submit" value="Logout">
        </form>
    </div>
    </div>


    <div class="container">
    <?php
        // Define an array of your categories (you can fetch this from your database as well)
        $categories = array("Gypsum", "Painting", "Fitting", "Terrazzo", "Cabroids");

        // Loop through each category
        foreach ($categories as $category) {
        // Fetch images from the database based on the current category
        $sql = "SELECT * FROM image WHERE category = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $category);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Output the category title
        echo '<div class="category">';
        echo '<h2>' . $category . '</h2>';

        // Create a container for the images in this category
        echo '<div id="' . strtolower($category) . '" class="image-grid">';

        // Loop through the fetched images and display them
        while ($row = mysqli_fetch_assoc($result)) {
            $fileName = $row['file_name'];

            // Assuming your images are stored in a directory called "uploads"
            $imagePath = "uploads/$category/$fileName";

            echo '<div class="image-item">';
            echo '<a href="' . $imagePath . '" data-lightbox="' . strtolower($category) . '" data-title="' . $fileName . '">';
            echo '<img class="modal-trigger" src="' . $imagePath . '" alt="' . $fileName . '">';
            echo '</a>';

            // Add a delete button with a form for each image
            echo '<form method="post" action="delete_image.php" class="delete-form">';
            echo '<input type="hidden" name="image_id" value="' . $row['image_id'] . '">';
            echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
            echo '</form>';

            echo '</div>';
            }

        echo '</div>'; // Close the container for the images
        echo '</div>'; // Close the category container
        }
    ?>
    </div>

    <!-- Footer Start -->
<div class="container-fluid bg-dark text-body footer mt-5 pt-5 px-0 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h3 class="text-light mb-4">Contact Us</h3>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>Mombasa, KENYA</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary me-3"></i>+254 718-893-450</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary me-3"></i>abdulpaintsanddesign@gmail.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-square btn-outline-body me-1" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-square btn-outline-body me-1" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-outline-body me-1" href="#"><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-square btn-outline-body me-0" href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h3 class="text-light mb-4">Our Services</h3>
                <a class="btn btn-link" href="gallery.php?category=gypsum">Gypsum</a>
                <a class="btn btn-link" href="gallery.php?category=painting">Painting</a>
                <a class="btn btn-link" href="gallery.php?category=terrazzo">Terrazzo</a>
                <a class="btn btn-link" href="gallery.php?category=fitting">Fitting</a>
                <a class="btn btn-link" href="gallery.php?category=cabroids">Cabroids</a>
                <a class="btn btn-link" href="gallery.php?category=ruff_and_tuff">Ruff &amp; Tuff</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 class="text-light mb-4">Quick Links</h3>
                <a class="btn btn-link" href="#">About Us</a>
                <a class="btn btn-link" href="#">Contact Us</a>
                <a class="btn btn-link" href="#">Our Services</a>
                <a class="btn btn-link" href="#">Privacy Policy</a>
                <a class="btn btn-link" href="#">Terms & Conditions</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 class="text-light mb-4">Newsletter</h3>
                <p>Subscribe to our newsletter to get the latest updates and offers.</p>
                <div class="position-relative w-100 mt-3">
                    <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Your Email">
                    <button type="button" class="btn btn-primary position-absolute top-0 end-0 mt-1 me-2 rounded-pill">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-center text-md-start py-4 px-4 px-lg-5">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="text-primary" href="#">Abdul Paints and Designs</a>, All Rights Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="footer-menu">
                    <a href="#">Home</a>
                    <a href="#">Cookies</a>
                    <a href="#">Help</a>
                    <a href="#">FQAs</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>

</html>


    <script>

        // Function to delete an image and update the gallery
    function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        // Send an AJAX request to delete_image.php
        $.ajax({
            type: 'POST',
            url: 'delete_image.php',
            data: { id: imageId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Image deleted successfully, update the gallery
                    $('#image_' + imageId).remove();
                    // Display a success message
                    $('#successMessage').html('<div class="alert alert-success">Image deleted successfully.</div>');
                } else {
                    // Handle deletion error
                    $('#successMessage').html('<div class="alert alert-danger">Error: ' + response.message + '</div>');
                }
            },
            error: function () {
                // Handle AJAX error
                $('#successMessage').html('<div class="alert alert-danger">AJAX request failed.</div>');
            }
        });
    }
    }

    </script>

     <style>
        /* Add the provided CSS code here */
        .upload-logout-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px; /* Add spacing between the two forms */
        }

        .upload-form {
            width: 70%; /* Adjust the width as needed */
        }

        .logout-form {
            text-align: right;
            width: 25%; /* Adjust the width as needed */
        }

        /* Style the buttons for better visibility */
        .upload-form input[type="submit"],
        .logout-form input[type="submit"] {
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .upload-form input[type="submit"]:hover,
        .logout-form input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>

