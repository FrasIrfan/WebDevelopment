<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$currentUserId = $_SESSION['userid'];

if (!$currentUserId) {
    echo "User not logged in.";
    exit();
}

// Get today's date
$today = date('Y-m-d');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendanceDate = $today; // Always use today's date
    $status = isset($_POST['Status']) ? $_POST['Status'] : '';

    if (!empty($status)) {
        // Check if an attendance record already exists for the user on the given date
        $check_sql = "SELECT * FROM Attendances WHERE UserID = '$currentUserId' AND AttendanceDate = '$attendanceDate'";
        $check_result = $mysqli->query($check_sql);

        if (!$check_result) {
            echo "Error checking attendance: " . $mysqli->error;
        } else if ($check_result->num_rows > 0) {
            // Record already exists, so display a message indicating that attendance is already marked
            echo "<div class='alert alert-warning' role='alert'>";
            echo "Attendance is already marked.";
            echo "</div>";
        } else {
            // SQL to insert data with the selected status
            $sql = "INSERT INTO Attendances (UserID, AttendanceDate, Status) VALUES ('$currentUserId', '$attendanceDate', '$status')";

            // Execute the query
            if ($mysqli->query($sql)) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Attendance marked successfully!";
                echo "</div>";
            } else {
                echo "Error: " . $mysqli->error;
            }
        }
    } else {
        echo "Please fill out all required fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Mark Attendance</h2>
        <p class="alert alert-info">Please note: If you do not mark your attendance today, you will be automatically marked as "Absent".</p>
        <form method="POST" action="markAttendance.php">
            <div class="mb-3">
                <label for="Date" class="form-label">Date</label>
                <input type="date" name="Date" id="Date" class="form-control" value="<?php echo "$today"; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label">Attendance Status</label>
                <select name="Status" id="Status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Present">Present</option>
                    <!-- <option value="Absent">Absent</option> -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
        <!-- <div class="mt-3">
            <a href="readAttendance.php" class="btn btn-secondary">See Attendance List</a>
        </div> -->
        <div class="mt-3">
            <a href="userDashboard.php" class="btn btn-secondary">Go Back</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>