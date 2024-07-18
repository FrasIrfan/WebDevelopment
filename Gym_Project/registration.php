<?php
include "config.php";  // Using database connection file here

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Retrieve form data
    $fname = $_POST['fname'];
    $lname= $_POST['lname'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // SQL to insert data
    $sql = "INSERT INTO Registrations (fname, lname, phone, username, password, email) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare statement with mysqli
    $statement = $mysqli->prepare($sql);

    // Check if the statement is prepared
    // If the statement is not prepared, show an error
    // If the statement is prepared, bind parameters and execute the statement
    // If the statement is executed, show a success message
    // If the statement is not executed, show an error
    // Close the statement
    if ($statement === false) {
        // Handle error
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        // Bind parameters and execute statement
        $statement->bind_param("ssssss", $fname, $lname, $phone, $username, $password, $email);

        // Execute statement
        if ($statement->execute()) {
            echo "Signup successful!";
        } else {
            // Handle error
            echo "Error: " . $statement->error;
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
    <title>Signup Form</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Signup Form</h2>
        <form method="POST" action="registration.php">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
        <!-- Button to login -->
        <div class="mt-3">
            <a href="login.php" class="btn btn-secondary">Login</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
