<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Assuming the user is logged in, and their ID is stored in the session
$userID = $_SESSION['userid'];
print_r($userID); // Adjust based on your session variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $timingID = isset($_POST['timing']) ? $_POST['timing'] : null;

    if ($timingID) {
        // Insert the selected timing into the UserTimings table
        $sql = "INSERT INTO UserTimings (UserID, TimingID) VALUES (?, ?)";
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            $statement->bind_param("ii", $userID, $timingID); // Bind user ID and timing ID as integers

            if ($statement->execute()) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Timing selection saved!";
                echo "</div>";
            } else {
                echo "Error: " . $statement->error;
            }

            $statement->close();
        }
    } else {
        echo "Please select a timing.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Timing</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Select Timing</h2>
        <form method="POST" action="UserTimings.php">
            <div class="mb-3">
                <label for="timing" class="form-label">Select Timing</label>
                <select name="timing" id="timing" class="form-control" required>
                    <option value="">Select timing</option>
                    <option value="1">Morning</option>
                    <option value="2">Evening</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Timing</button>
        </form>
        <!-- <div class="mt-3">
            <a href="readTimings.php" class="btn btn-secondary">See Selected Timings</a>
        </div> -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
