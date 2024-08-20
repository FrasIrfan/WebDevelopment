<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
session_start();

// Ensure the session userid is set
if (!isset($_SESSION['userid'])) {
    die("Session is not set. Please log in first.");
}

// Database configuration
// $host = 'localhost';
// $user = 'root';
// $pass = '';
// $dbname = 'GYM';

// // Create database connection
// $mysqli = new mysqli($host, $user, $pass, $dbname);

// // Check for connection error
// if ($mysqli->connect_error) {
//     die("Connection failed: " . $mysqli->connect_error);
// }

// Get the current logged-in user's ID
$userId = $_SESSION['userid'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch user input directly from POST data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Execute the update query
    $query = "UPDATE Users SET fname = '$fname', lname = '$lname', phone = '$phone', email = '$email', username = '$username' WHERE ID = $userId";
    if ($mysqli->query($query)) {
        header('Location: userDetails.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user. Please try again.</div>";
    }
}

// Fetch current user details
$query = "SELECT fname, lname, phone, email, username FROM Users WHERE ID = $userId";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $fname = htmlspecialchars($user['fname']);
    $lname = htmlspecialchars($user['lname']);
    $phone = htmlspecialchars($user['phone']);
    $email = htmlspecialchars($user['email']);
    $username = htmlspecialchars($user['username']);
} else {
    die("User not found.");
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="post">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?= htmlspecialchars($fname) ?>" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?= htmlspecialchars($lname) ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="userDetails.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
