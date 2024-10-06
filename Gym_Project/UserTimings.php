<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$userID = $_SESSION['userid'];
// print_r($userID); 

$currentTiming = null;

// Fetch the current timing for the logged-in user
$sql = "SELECT TimingID FROM UserTimings WHERE UserID = ?";
$statement = $mysqli->prepare($sql);

if ($statement === false) {
    echo "Error preparing statement: " . $mysqli->error;
} else {
    $statement->bind_param("i", $userID);

    if ($statement->execute()) {
        $result = $statement->get_result();
        if ($row = $result->fetch_assoc()) {
            $currentTiming = $row['TimingID'];
        }
    } else {
        echo "Error: " . $statement->error;
    }

    $statement->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $timingID = isset($_POST['timing']) ? $_POST['timing'] : null;

    if ($timingID) {
        // Update the selected timing into the UserTimings table
        $sql = "REPLACE INTO UserTimings (UserID, TimingID) VALUES (?, ?)";
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
    <link rel="stylesheet" href="info.css">

</head>

<body>
    <div class="container mt-5">
        <h2>Select Timing</h2>

        <!-- Display current timing -->
        <div class="mb-4">
            <h4>Your Current Timing:</h4>
            <p>
                <?php
                if ($currentTiming == 1) {
                    echo "Morning";
                } elseif ($currentTiming == 2) {
                    echo "Evening";
                } else {
                    echo "No timing selected";
                }
                ?>
            </p>
        </div>

        <!-- Form for selecting new timing -->
        <form method="POST" action="UserTimings.php">
            <div class="mb-3">
                <label for="timing" class="form-label">Select Timing</label>
                <select name="timing" id="timing" class="form-control" required>
                    <option value="">Select timing</option>
                    <option value="1" <?php echo ($currentTiming == 1) ? 'selected' : ''; ?>>Morning</option>
                    <option value="2" <?php echo ($currentTiming == 2) ? 'selected' : ''; ?>>Evening</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Timing</button>
            <div class="mt-3">
                <a href="userDashboard.php" class="btn btn-primary">Go back</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>