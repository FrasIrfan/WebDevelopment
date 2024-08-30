<?php
include "config.php";  // Using database connection file here

session_start();
$currentUserId = $_SESSION['userid'];
// print_r($currentUserId);

// Display errors for debugging
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
    $userType = isset($_POST['UserType']) ? $_POST['UserType'] : null;
    $createdBy = $currentUserId;

    // Ensure required fields are filled
    if ($fname && $lname && $phone && $email && $username && $password && $userType && $createdBy !== null) {
        // Check if email already used
        $sqlCheckEmail = "SELECT ID FROM Users WHERE email = ?";
        $CheckEmail = $mysqli->prepare($sqlCheckEmail);
        $CheckEmail->bind_param("s", $email);
        $CheckEmail->execute();
        $CheckEmail->store_result();

        if ($CheckEmail->num_rows > 0) {
            // Email already used
            echo "<div class='alert alert-danger' role='alert'>";
            echo "Email already used! Use another email.";
            echo "</div>";
        } else {
            // Check if phone number is already used
            $sqlCheckPhoneNumber = "SELECT ID FROM Users WHERE phone = ?";
            $CheckPhoneNumber = $mysqli->prepare($sqlCheckPhoneNumber);
            $CheckPhoneNumber->bind_param("s", $phone); 
            $CheckPhoneNumber->execute();
            $CheckPhoneNumber->store_result();

            if ($CheckPhoneNumber->num_rows > 0) {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "This phone number is already associated with an account! Use a different phone number.";
                echo "</div>";
            } else {
                // Check if the username already exists
                $sqlCheckUsername = "SELECT ID FROM Users WHERE username = ?";
                $CheckUsername = $mysqli->prepare($sqlCheckUsername);
                $CheckUsername->bind_param("s", $username);
                $CheckUsername->execute();
                $CheckUsername->store_result();

                if ($CheckUsername->num_rows > 0) {
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "Username already exists! Use a different username.";
                    echo "</div>";
                } else {
                    // SQL to insert data
                    $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, userType, CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    // Prepare statement with mysqli
                    $statement = $mysqli->prepare($sql);

                    if ($statement === false) {
                        echo "Error preparing statement: " . $mysqli->error;
                    } else {
                        // Bind parameters and execute statement
                        $statement->bind_param(
                            "sssssssi",
                            $fname,       // String
                            $lname,       // String
                            $phone,       // String
                            $email,       // String
                            $username,    // String
                            $password,    // String
                            $userType,    // String (ENUM)
                            $createdBy    // Integer
                        );
                        // Execute statement
                        if ($statement->execute()) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo "User added successfully!";
                            echo "</div>";
                        } else {
                            echo "Error: " . $statement->error;
                        }

                        // Close statement
                        $statement->close();
                    }
                }
            }
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>";
        echo "Please fill out all required fields.";
        echo "</div>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Add User</h2>
        <form method="POST" action="addUser.php">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname">
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" pattern="\d{11}" title="Phone number must be exactly 11 digits" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="UserType" class="form-label">User Type</label>
                <select name="UserType" id="UserType" class="form-control">
                    <option value="">Select a type</option>
                    <!-- <option value="owner">Owner</option> -->
                    <option value="member">Member</option>
                    <option value="trainer">Trainer</option>
                    <option value="worker">Worker</option>
                    <option value="janitor">Janitor</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add New User</button>
        </form>
        <div class="mt-3">
            <a href="readUsers.php" class="btn btn-secondary">See all Users</a>
        </div>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>