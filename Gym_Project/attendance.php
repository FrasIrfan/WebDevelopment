<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$sql = "SELECT ID, username, userType FROM Users WHERE userType = 'member'";

// store data 
$storedata = $mysqli->query($sql);

if (!$storedata) {
    echo "Error fetching data: " . $mysqli->error;
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $UserID = isset($_POST['UserID']) ? $_POST['UserID'] : null;
    $Date = isset($_POST['Date']) ? $_POST['Date'] : null;
    $Status = isset($_POST['Status']) ? $_POST['Status'] : null;

    if (!empty($UserID) && !empty($Status) && !empty($Date)) {
        // Check if an attendance record already exists for the user on the given date
        $check_sql = "SELECT * FROM Attendances WHERE UserID = ? AND Date = ?";
        $check_statement = $mysqli->prepare($check_sql);

        if ($check_statement === false) {
            echo "Error preparing check statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $check_statement->bind_param(
                "is",
                $UserID,
                $Date
            );

            // Execute statement
            $check_statement->execute();
            $result = $check_statement->get_result();

            if ($result->num_rows > 0) {
                // Record already exists
                echo "<div class='alert alert-warning' role='alert'>";
                echo "Attendance for this user on this date already exists.";
                echo "</div>";
            } else {
                // SQL to insert data
                $sql = "INSERT INTO Attendances (UserID, Status, Date) VALUES (?, ?, ?)";
                $statement = $mysqli->prepare($sql);

                if ($statement === false) {
                    echo "Error preparing statement: " . $mysqli->error;
                } else {
                    // Bind parameters and execute statement
                    $statement->bind_param(
                        "iss",
                        $UserID,
                        $Status,
                        $Date
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
            }

            // Close the check statement
            $check_statement->close();
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
    <title>Attendance</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Attendance</h2>
        <form method="POST" action="attendance.php">
            <div class="mb-3">
                <label for="UserID" class="form-label">Select User</label>
                <select name="UserID" id="UserID" class="form-control" required>
                    <?php
                    if ($storedata->num_rows > 0) { // Check if the query returned any rows
                        // Loop through each row in the result set
                        while ($row = $storedata->fetch_assoc()) {
                    ?>
                    <option value="<?= htmlspecialchars($row['ID']); ?>">
                        <?= htmlspecialchars($row['username']); ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="Date" class="form-label">Date</label>
                <input type="date" name="Date" id="Date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="Status" class="form-label">Attendance Status</label>
                <select name="Status" id="Status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
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
