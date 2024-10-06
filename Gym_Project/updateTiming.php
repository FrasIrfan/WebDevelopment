<?php
require_once 'timingClass.php'; // Include the Timing class

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Shifts = isset($_POST['Shifts']) ? $_POST['Shifts'] : null;
    $startTime = isset($_POST['startTime']) ? $_POST['startTime'] : null;
    $endTime = isset($_POST['endTime']) ? $_POST['endTime'] : null;

    if ($Shifts && $startTime && $endTime) {
        $timing = new Timing();
        $message = $timing->updateTiming($Shifts, $startTime, $endTime);
    } else {
        $message = "Please fill out all required fields.";
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
    <link rel="stylesheet" href="info.css">

</head>
<body>
    <div class="container mt-5">
        <h2>Update Timing</h2>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="updateTiming.php">
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
                        for ($minute = 0; $minute < $interval; $minute += 60) {
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
                        for ($minute = 0; $minute < $interval; $minute += 60) {
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
