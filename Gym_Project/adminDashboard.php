<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start a session
session_start();

// Include the config file
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Return to the login page
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leo Fitness</title>
    <link rel="icon" href="image/icon.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhai+2:wght@500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="dashboard.css" />
</head>

<body>
    <header class="header">
        <!-- left box for logo -->
        <div class="left">
            <img src="image/custom_logo.png" />
        </div>


        <!-- mid box for navigation -->
        <?php include('nav.php'); ?> <!-- Include the external nav file -->



        <!-- right box for buttons and notifications -->
        <div id="logout" class="right">
            <form action="logout.php" method="POST">
                <button type="submit">Logout</button>
            </form>
        </div>



        <div class="container">
            <h1>Welcome <?php echo $_SESSION['username']; ?></h1>
            <p>
                Hope your doing great !
                <br>
                How are you owner
                <br>
                What changes would you like to make today?
                <br>
            </p>

        </div>

    </header>

    <footer class="footer">
        <p>
            © 2024 Leo Fitness Club. All rights reserved.
        </p>
    </footer>

</body>

</html>