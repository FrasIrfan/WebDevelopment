<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $PackageName = isset($_POST['PackageName']) ? $_POST['PackageName'] : null;
    $PackagePrice = isset($_POST['PackagePrice']) ? $_POST['PackagePrice'] : null;

    if (!empty($PackageName) && !empty($PackagePrice)) {
        // SQL to update data
        $sql = "UPDATE Packages SET PackagePrice = ? WHERE PackageName = ?";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement

            // $statement->bind_param(
            //     "si",            
            //     $PackageName,     
            //     $PackagePrice   
            // );
            $statement->bind_param(
                "is",            
                $PackagePrice,   
                $PackageName     
            );

            // Execute statement
            if ($statement->execute()) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Package Updated!";
                // echo print_r($_SESSION);
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
    <title>Packages</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Update Packages</h2>
        <form method="POST" action="packagePrice.php">
            <div class="mb-3">
                <label for="PackageName" class="form-label">Package Name</label>
                <select name="PackageName" id="PackageName" class="form-control">
                    <option value="">Select Package</option>
                    <option value="silver">Silver</option>
                    <option value="gold">Gold</option>
                    <option value="platinum">Platinum</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="PackagePrice" class="form-label">Package Price</label>
                <input type="number" class="form-control" id="PackagePrice" name="PackagePrice" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Package</button>
        </form>
        <div class="mt-3">
            <a href="readPackages.php" class="btn btn-secondary">See Packages</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
