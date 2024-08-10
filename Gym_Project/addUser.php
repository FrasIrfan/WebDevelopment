<?php
include "config.php";  // Using database connection file here

session_start();
$currentUserId = $_SESSION['username'];

// Fetch the user type of the current logged-in user
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// $createdBy = null;
// $sqlUserType = "SELECT username FROM Users WHERE ID = ?";
// $stmtUserType = $mysqli->prepare($sqlUserType);
// $stmtUserType->bind_param("i", $currentUserId);
// $stmtUserType->execute();
// $resultUserType = $stmtUserType->get_result();

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
        // SQL to insert data
        $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, userType , CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

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
                $userType,
                $createdBy
                // String (ENUM)
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
                <input type="text" class="form-control" id="phone" name="phone">
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