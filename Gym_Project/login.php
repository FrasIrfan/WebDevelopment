<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Make sure this file contains a mysqli connection $mysqli

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL to fetch the user
    $sql = "SELECT id, username, password FROM Users WHERE username = ?";
    $statement = $mysqli->prepare($sql);

    if ($statement === false) {
        // Handle error
        echo "Error: " . $mysqli->error;
    } else {
        // Bind parameters and execute statement
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();


            // Check if the password and userType are being correctly fetched
            // echo "Password from DB: " . $user['password'] . "<br>";
            // echo "User type from DB: " . $user['userType'] . "<br>";
            // echo "Provided password: " . $password . "<br>";
            // Verify the password and check if owner has logged in
            // if (password_verify($password, $user['password']) && $user['userType'] === "owner") {
            //     // Start a session and set session variables
            //     $_SESSION['userid'] = $user['id'];
            //     $_SESSION['username'] = $user['username'];
            //     // Redirect to a dashboard or another page
            //     echo "Owner has logged in";
            //     // header("Location: dashboard.php");
            //     exit();
            // }

            
            //Verify the password
            if (password_verify($password, $user['password'])) {
                // Start a session and set session variables
                $_SESSION['userid'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                // Redirect to a dashboard or another page
                header("Location: dashboard.php");
                exit();
            }
        } else {
            echo "Invalid password.";
        }

        // Close statement
        $statement->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="fras" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
            <!-- Button to register a new user -->
            <div class="mt-3">
                <a href="registration.php" class="btn btn-secondary">Register New User</a>
            </div>

        </form>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>