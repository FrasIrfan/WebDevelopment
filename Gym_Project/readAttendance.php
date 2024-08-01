<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Include the database configuration file to establish a database connection
include 'config.php';

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    // Terminate the script and display the connection error
    die("Connection failed: " . $mysqli->connect_error);
} else {
    // Display a success message if the connection is established
    echo "<div class='alert alert-success' role='alert'>";
    echo "Connected successfully to database: " . $dbname;
    echo "</div>";
}

// SQL query to select user names and attendance details by joining the Attendance and Users tables
$sql = "SELECT Users.username, Attendance.AttendanceStatus, Attendance.CreatedAt
        FROM Attendance
        JOIN Users ON Attendance.AttendanceOf = Users.ID";

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
        <h2>Attendance Sheet</h2>
        <?php if ($result && $result->num_rows > 0) : // Check if the query returned any rows 
        ?>
            <!-- Create a table to display the user list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Attendance of</th>
                        <th>Attendance Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row in the result set
                    while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['AttendanceStatus'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['CreatedAt'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : // If no rows were returned, display a message indicating no users found 
        ?>
            <div class="alert alert-warning" role="alert">
                No attendance records found.
            </div>
        <?php endif; ?>
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
