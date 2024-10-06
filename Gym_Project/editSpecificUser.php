<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database.php'; // Include your Database class
require_once 'userClass.php'; // Include your User class
session_start();

// Initialize Database connection
$db = new Database();

// Ensure the session userid is set
if (!isset($_SESSION['userid'])) {
    die("Session is not set. Please log in first.");
}

// Initialize User object
$currentUserId = $_SESSION['userid'];
$user = new User($db, $currentUserId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Update user details
    $updateQuery = "UPDATE Users SET fname = ?, lname = ?, phone = ?, email = ?, username = ? WHERE ID = ?";
    $params = [$fname, $lname, $phone, $email, $username, $currentUserId];

    if ($db->query($updateQuery, $params)) {
        header('Location: userDetails.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update user. Please try again.</div>";
    }
}

// Fetch current user details
$currentUserDetails = $user->getUserDetails();
if (!$currentUserDetails) {
    die("User not found.");
}

$fname = htmlspecialchars($currentUserDetails['fname']);
$lname = htmlspecialchars($currentUserDetails['lname']);
$phone = htmlspecialchars($currentUserDetails['phone']);
$email = htmlspecialchars($currentUserDetails['email']);
$username = htmlspecialchars($currentUserDetails['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">

</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="post">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?= $fname ?>" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?= $lname ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" required>
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

<?php
// Close the database connection
$db->close();
?>
