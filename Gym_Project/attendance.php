<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start();
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $AttendanceOf = isset($_POST['AttendanceOf']) ? $_POST['AttendanceOf'] : null;
    $AttendanceStatus = isset($_POST['AttendanceStatus']) ? $_POST['AttendanceStatus'] : null;

    // SQL to insert data
    $sql = "INSERT INTO Attendances (AttendanceOf, AttendanceStatus) VALUES (?, ?)";

    // Prepare statement with mysqli
    $statement = $mysqli->prepare($sql);

    if ($statement === false) {
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        // Bind parameters and execute statement
        $statement->bind_param(
            "is",
            $AttendanceOf, // Integer
            $AttendanceStatus    // String
        );

        // Execute statement
        if ($statement->execute()) {
            echo "<div class='alert alert-success' role='alert'>";
            echo "Attendance Added!";
            echo "</div>";
        } else {
            echo "Error: " . $statement->error;
        }

        // Close statement
        $statement->close();
    }
} else {
    echo "Please fill out all required fields.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Add Attendance</h2>
        <form method="POST" action="attendance.php">
            <div class="mb-3">
                <label for="AttendanceOf" class="form-label">Attendance of</label>
                <input type="text" class="form-control" id="AttendanceOf" name="AttendanceOf" required>
            </div>
            <div class="mb-3">
                <label for="AttendanceStatus" class="form-label">Attendance Status</label>
                <input type="text" class="form-control" id="AttendanceStatus" name="AttendanceStatus" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Attendance</button>
        </form>
        <div class="mt-3">
            <a href="readAttendance.php" class="btn btn-secondary">See Attendance List</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>