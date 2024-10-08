<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the new Database class
require_once 'database.php';

// Create a new Database instance
$db = new Database();

// SQL query to select user data
$sql = "SELECT ID, fname, lname, phone, email, username, CreatedAt, CreatedBy, UserType FROM Users WHERE UserType != ?";

try {
    // Execute the query using the new Database class
    $users = $db->query($sql, ['owner']);
} catch (Exception $e) {
    // Handle any database errors
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">


</head>

<body>
    <!-- Include navigation here -->
    <?php include('nav.php'); ?> <!-- Include the external nav file -->


    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>USERS LIST</h2>
            <div>
                <a href="addUser.php" class="btn btn-info">Add User</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php } elseif (empty($users)) { ?>
            <div class="alert alert-warning" role="alert">
                No users found.
            </div>
        <?php } else { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Created At</th>
                        <!-- <th>Created By</th> -->
                        <th>User Type</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?= $user['ID'] ?></td>
                            <td><?= $user['fname'] ?></td>
                            <td><?= $user['lname'] ?></td>
                            <td><?= $user['phone'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['CreatedAt'] ?></td>
                            <td><?= $user['UserType'] ?></td>
                            <td>
                                <a href="editUser.php?id=<?= $user['ID'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                            <td>
                                <a href="deleteUser.php?id=<?= $user['ID'] ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>