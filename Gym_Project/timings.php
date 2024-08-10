<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $Shifts = isset($_POST['Shifts']) ? $_POST['Shifts'] : null;
    $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : null;
    $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : null;

    if ($Shifts && $startTime && $endTime) {
        // SQL to update data
        $sql = "UPDATE Timings SET startTime = ?, endTime = ? WHERE Shifts = ?";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $statement->bind_param(
                "sss",
                $startTime, // String
                $endTime,   // String
                $Shifts     // String
            );

            // Execute statement
            if ($statement->execute()) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Timing Updated!";
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update Timing</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Timing</h2>
        <form method="POST" action="timings.php">
            <div class="mb-3">
                <label for="Shifts" class="form-label">Select Shift</label>
                <select name="Shifts" id="Shifts" class="form-control" required>
                    <option value="">Select timing</option>
                    <option value="morning">Morning</option>
                    <option value="evening">Evening</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="startTime" class="form-label">Start Time</label>
                <select class="form-control" id="startTime" name="startTime" required>
                    <?php
                    $interval = 60;
                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += $interval) {
                            $ampm = $hour < 12 ? 'AM' : 'PM';
                            $displayHour = $hour % 12 === 0 ? 12 : $hour % 12; 
                            $displayMinute = $minute < 10 ? "0$minute" : $minute;
                            $timeLabel = "$displayHour:$displayMinute $ampm";
                            $value = sprintf('%02d:%02d:00', $hour, $minute);
                            echo "<option value=\"$value\">$timeLabel</option>\n";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="endTime" class="form-label">End Time</label>
                <select class="form-control" id="endTime" name="endTime" required>
                    <?php
                    $interval = 60; 
                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += $interval) {
                            $ampm = $hour < 12 ? 'AM' : 'PM';
                            $displayHour = $hour % 12 === 0 ? 12 : $hour % 12; 
                            $displayMinute = $minute < 10 ? "0$minute" : $minute;
                            $timeLabel = "$displayHour:$displayMinute $ampm";
                            $value = sprintf('%02d:%02d:00', $hour, $minute);
                            echo "<option value=\"$value\">$timeLabel</option>\n";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Time</button>
        </form>
        <div class="mt-3">
            <a href="readTimings.php" class="btn btn-secondary">See Timings</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
