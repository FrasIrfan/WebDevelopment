<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'database.php';

$db = new Database();

// SQL query to select shifts and timings from the Timings table
$sql = "SELECT Shifts, startTime, endTime FROM Timings";

try {
    $timings = $db->query($sql); // Assuming this returns an array
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timings</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Timings</h2>
            <div>
                <a href="timings.php" class="btn btn-info">Update Timings</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } elseif (empty($timings)) { ?>
            <div class="alert alert-warning" role="alert">
                No timings found.
            </div>
        <?php } else { ?>
            <!-- Create a table to display the timings with Bootstrap classes for styling -->
            <table class="table table-bordered mt-3">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Shift</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timings as $timing) {
                        // Convert startTime and endTime to DateTime objects
                        $startTime = new DateTime($timing['startTime']);
                        $endTime = new DateTime($timing['endTime']);

                        // Format the times to AM/PM format
                        $formattedStartTime = $startTime->format('h:i A');
                        $formattedEndTime = $endTime->format('h:i A');
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($timing['Shifts']) ?></td>
                            <td><?= htmlspecialchars($formattedStartTime) ?></td>
                            <td><?= htmlspecialchars($formattedEndTime) ?></td>
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
