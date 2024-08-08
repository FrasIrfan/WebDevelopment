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
    // Terminate the script and display the connection error
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Check if the form is submitted to update user details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $userType = $_POST['UserType'];

        // Prepare and execute the SQL query to update the user
        $stmt = $mysqli->prepare("UPDATE Users SET fname = ?, lname = ?, phone = ?, email = ?, username = ?, UserType = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $fname, $lname, $phone, $email, $username, $userType, $userId);
        if ($stmt->execute()) {
            // Redirect to the user list page after successful update
            header('Location: readUsers.php');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to update user. Please try again.</div>";
        }
        $stmt->close();
    }

    // Prepare and execute the SQL query to fetch the current user details
    $stmt = $mysqli->prepare("SELECT fname, lname, phone, email, username, UserType FROM Users WHERE id = ?");
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
    <title>Edit User</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
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
            <div class="mb-3">
                <label for="UserType" class="form-label">User Type</label>
                <select name="UserType" id="UserType" class="form-control" required>
                    <option value="">Select a type</option>
                    <option value="member" <?= $userType === 'member' ? 'selected' : '' ?>>Member</option>
                    <option value="trainer" <?= $userType === 'trainer' ? 'selected' : '' ?>>Trainer</option>
                    <option value="worker" <?= $userType === 'worker' ? 'selected' : '' ?>>Worker</option>
                    <option value="janitor" <?= $userType === 'janitor' ? 'selected' : '' ?>>Janitor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="readUser.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>


    <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Close the database connection to free up resources
$mysqli->close();
?>