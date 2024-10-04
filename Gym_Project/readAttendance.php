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

// SQL query to select attendance data
$sql = "SELECT Users.username, Users.ID, Attendances.Status, Attendances.AttendanceDate
        FROM Attendances
        JOIN Users ON Attendances.UserID = Users.ID";

try {
    // Execute the query using the new Database class
    $attendance = $db->query($sql);
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
    <title>Attendance Sheet</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Attendance Sheet</h2>
            <div>
                <!-- <a href="markAttendance.php" class="btn btn-info">Add Attendance</a> -->
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php }
        elseif (empty($attendance)) { ?>
            <div class="alert alert-warning" role="alert">
                No attendance records found.
            </div>
        <?php }
        else { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>User ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance as $row) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['ID']) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                            <td><?= htmlspecialchars($row['AttendanceDate']) ?></td>
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
