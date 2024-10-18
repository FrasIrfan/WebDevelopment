<?php
session_start();
require_once 'database.php';
require_once 'userClass.php';

// Create a new Database instance
$db = new Database();

// Current user ID from the session
$currentUserId = $_SESSION['userid'] ?? null;

// Check if user ID is set
if ($currentUserId === null) {
    die("User ID is not set in session.");
}

// Instantiate the User class
$user = new User($db, $currentUserId);

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';
$messageType = ''; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data and validate
    $fname = $_POST['fname'] ?? null;
    $lname = $_POST['lname'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $userType = $_POST['UserType'] ?? null;

    // Ensure all required fields are filled
    if ($fname && $lname && $phone && $email && $username && $password && $userType) {
        $result = $user->addUser($fname, $lname, $phone, $email, $username, $password, $userType);
        $message = $result['message'];
        $messageType = $result['status']; // Ensure this is 'success' or 'error'
    } else {
        $message = "Please fill out all required fields.";
        $messageType = 'error';
    }
}

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">
</head>

<body>
    
    <div class="container mt-5">
        <h2>Add User</h2>
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
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
