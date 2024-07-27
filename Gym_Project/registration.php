<?php
include "config.php";  // Using database connection file here

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $fname = isset($_POST['fname']) ? $_POST['fname'] : null;
    $lname = isset($_POST['lname']) ? $_POST['lname'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $createdBY = isset($_POST['CreatedBY']) ? $_POST['CreatedBY'] : null;
    $userType = isset($_POST['UserType']) ? $_POST['UserType'] : null;
    $userTimings = isset($_POST['UserTimings']) ? $_POST['UserTimings'] : null;
    $Age = isset($_POST['Age']) ? $_POST['Age'] : null;
    $Weight = isset($_POST['Weight']) ? $_POST['Weight'] : null;
    $Height = isset($_POST['Height']) ? $_POST['Height'] : null;

    // Ensure required fields are filled
    if ($fname && $lname && $phone && $email && $username && $password && $userType && $userTimings && $Age !== null) {
        // SQL to insert data
        $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, createdBY, userType, userTimings, Age, Weight, Height) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $statement->bind_param(
                "ssssssississ",
                $fname,       // String
                $lname,       // String
                $phone,       // String
                $email,       // String
                $username,    // String
                $password,    // String
                $createdBY,   // Integer
                $userType,    // String (ENUM)
                $userTimings, // String (ENUM)
                $Age,         // Integer
                $Weight,      // String
                $Height       // String
            );

            // Execute statement
            if ($statement->execute()) {
                echo "Signup successful!";
            } else {
                echo "Error: " . $statement->error;
            }

            // Close statement
            $statement->close();
        }
    } else {
        echo "Please fill out all required fields.";
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
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
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
        <label for="createdBy" class="form-label">Created By</label>
        <input type="text" class="form-control" id="createdBy" name="CreatedBY">
    </div>
    <div class="mb-3">
        <label for="UserType" class="form-label">User Type</label>
        <select name="UserType" id="UserType" class="form-control" required>
            <option value="">Select a type</option>
            <option value="owner">Owner</option>
            <option value="member">Member</option>
            <option value="trainer">Trainer</option>
            <option value="worker">Worker</option>
            <option value="janitor">Janitor</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="UserTimings" class="form-label">User Timings</label>
        <select name="UserTimings" id="UserTimings" class="form-control" required>
            <option value="">Select a timing</option>
            <option value="morning">Morning</option>
            <option value="evening">Evening</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="Age" class="form-label">Age</label>
        <input type="number" class="form-control" id="Age" name="Age" required>
    </div>

    <div class="mb-3">
        <label for="Weight" class="form-label">Weight</label>
        <input type="number" class="form-control" id="Weight" name="Weight">
    </div>

    <div class="mb-3">
        <label for="Height" class="form-label">Height</label>
        <input type="number" class="form-control" id="Height" name="Height">
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