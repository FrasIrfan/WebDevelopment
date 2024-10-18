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
        <div class="mid">
            <ul class="navbar">
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readUsers.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readUsers.php">Users</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readAttendance.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readAttendance.php">Attendance</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readEquipment.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readEquipment.php">Equipment</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readTimings.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readTimings.php">Timings</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readPayments.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readPayments.php">Payments</a>
                </li>
                <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readPackages.php' ? 'active' : ''; ?>">
                    <a href="/Gym_Project/readPackages.php">Packages</a>
                </li>
            </ul>
        </div>


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