<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Assuming the user is logged in, and their ID is stored in the session
$userID = $_SESSION['userid'];
print_r($userID);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $packageID = isset($_POST['package']) ? $_POST['package'] : null;

    if ($packageID) {
        // Insert the selected package into the UserPackage table
        $sql = "INSERT INTO UserPackage (UserID, PackageID) VALUES (?, ?)";
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            $statement->bind_param("ii", $userID, $packageID); // Bind user ID and package ID as integers

            if ($statement->execute()) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Package selected succesfully!";
                echo "</div>";
            } else {
                echo "Error: " . $statement->error;
            }

            $statement->close();
        }
    } else {
        echo "Please select a package.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Package</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Select Package</h2>
        <form method="POST" action="UserPackage.php">
            <div class="mb-3">
                <label for="package" class="form-label">Select Package</label>
                <select name="package" id="package" class="form-control" required>
                    <option value="">Select a package</option>
                    <?php
                    // Fetch packages from the Packages table
                    $result = $mysqli->query("SELECT PackageID, PackageName,PackagePrice FROM Packages");

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['PackageID'] . "'>" . $row['PackageName'] . "  " . $row['PackagePrice']. "Rs".  "</option>";
                        }
                    } else {
                        echo "<option value=''>No packages available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Package</button>
        </form>
        <!-- <div class="mt-3">
            <a href="readPackages.php" class="btn btn-secondary">See Selected Packages</a>
        </div> -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
