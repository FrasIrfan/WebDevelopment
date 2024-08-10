<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file
include 'config.php';
session_start();

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Check if the form is submitted to delete user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        // Prepare and execute the SQL query to delete the user
        $stmt = $mysqli->prepare("DELETE FROM Users WHERE ID = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            // Redirect to the user list page after successful deletion
            header('Location: readUsers.php');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to delete user. Please try again.</div>";
        }
        $stmt->close();
    }

    // Prepare and execute the SQL query to fetch the current user details
    $stmt = $mysqli->prepare("SELECT fname, lname, phone, email, username, UserType FROM Users WHERE ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($fname, $lname, $phone, $email, $username, $userType);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>No user ID provided.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete User</h2>
        <p>Are you sure you want to delete the following user?</p>
        <ul>
            <li><strong>First Name:</strong> <?= $fname ?></li>
            <li><strong>Last Name:</strong> <?= $lname ?></li>
            <li><strong>Phone Number:</strong> <?= $phone ?></li>
            <li><strong>Email:</strong> <?= $email ?></li>
            <li><strong>Username:</strong> <?= $username ?></li>
            <li><strong>User Type:</strong> <?= $userType ?></li>
        </ul>
        <form method="post">
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</button>
            <a href="readUsers.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection to free up resources
$mysqli->close();
?>
