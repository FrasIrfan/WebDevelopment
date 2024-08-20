<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// print_r($_SESSION['username']);
// print_r($_SESSION);
// $CreatedBy = $_SESSION['username'];
// Include the database configuration file to establish a database connection
include 'config.php';

// Check if there is a connection error with the database
// if ($mysqli->connect_error) {
//     // Terminate the script and display the connection error
//     die("Connection failed: " . $mysqli->connect_error);
// }

// SQL query to select first name, last name, and email from the registrations table
$sql = "SELECT ID,fname, lname,phone, email,username, CreatedAt,CreatedBy, UserType FROM Users WHERE UserType != 'owner'";
// Execute the SQL query and store the result in $result
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>User List</h2>
            <div>
                <a href="addUser.php" class="btn btn-info">Add User</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>

        <?php if ($result->num_rows > 0) { // Check if the query returned any rows 
        ?>
            <!-- Create a table to display the user list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Created At</th>
                        <th>Created By</th>

                        <th>User Type</th>
                        <!-- <th>Actions</th> -->
                        <!--  -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row in the result set
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <!-- Output the first name, last name, and email of each user -->
                            <td><?= $row['ID'] ?></td>
                            <td><?= $row['fname'] ?></td>
                            <td><?= $row['lname'] ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['CreatedAt'] ?></td>
                            <td><?= $row['CreatedBy'] ?></td>
                            <td><?= $row['UserType'] ?></td>
                            <td>
                                <a href="editUser.php?id=<?= $row['ID'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                            <td>
                                <a href="deleteUser.php?id=<?= $row['ID'] ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { // If no rows were returned, display a message indicating no users found 
        ?>
            <div class="alert alert-warning" role="alert">
                No users found.
            </div>
        <?php } ?>

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