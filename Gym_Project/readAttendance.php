<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT Users.username, Users.ID, Attendances.Status, Attendances.Date
        FROM Attendances
        JOIN Users ON Attendances.UserID = Users.ID";

// Execute the SQL query and store the result in $result
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Sheet</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Attendance Sheet</h2>
            <a href="attendance.php" class="btn btn-info">Add Attendance</a>
        </div>
        <?php if ($result && $result->num_rows > 0) { // Check if the query returned any rows 
        ?>
            <!-- Create a table to display the user list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Attendance of</th>
                        <th>UserID</th>

                        <th>Attendance Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row in the result set
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>

                            <td><?= $row['username'] ?></td>
                            <td><?= $row['ID'] ?></td>

                            <td><?= $row['Status'] ?></td>
                            <td><?= $row['Date'] ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning" role="alert">
                No attendance records found.
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