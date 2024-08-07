<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file to establish a database connection
include 'config.php';
session_start();

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    // Terminate the script and display the connection error
    die("Connection failed: " . $mysqli->connect_error);
} 

// SQL query to select first name, last name, and email from the registrations table
$sql = "SELECT PackageName, PackagePrice, CreatedAt FROM Packages";
// Execute the SQL query and store the result in $result
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Packages</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Packages List</h2>
            <a href="packagePrice.php" class="btn btn-info">Update Package</a>
        </div>
        <?php if ($result->num_rows > 0) { // Check if the query returned any rows 
        ?>
            <!-- Create a table to display the user list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Package Name</th>
                        <th>Package Price</th>
                        <th>Updated at</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row in the result set
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <!-- Output the first name, last name, and email of each user -->
                            <td><?= $row['PackageName'] ?></td>
                            <td><?= $row['PackagePrice'] ?></td>
                            <td><?= $row['CreatedAt'] ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { // If no rows were returned, display a message indicating no users found 
        ?>
            <div class="alert alert-warning" role="alert">
                No Packages found.
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
?></table>