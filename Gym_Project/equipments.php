<?php
include "config.php";  // Using database connection file here

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $equipmentName = isset($_POST['equipmentName']) ? $_POST['equipmentName'] : null;
    $buyingPrice = isset($_POST['buyingPrice']) ? $_POST['buyingPrice'] : null;
    $AddedBy = isset($_POST['AddedBy']) ? $_POST['AddedBy'] : null;
    
    // Ensure required fields are filled
    if ($equipmentName && $buyingPrice && $AddedBy && $email && $username && $password && $userType && $userTimings && $Age !== null) {
        // SQL to insert data
        $sql = "INSERT INTO Users (equipmentName, buyingPrice, AddedBy, email, username, password, createdBY, userType, userTimings, Age, Weight, Height) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $statement->bind_param(
                "sis",
                $equipmentName,       // String
                $buyingPrice,       // Integer
                $AddedBy,       // String
                
            );

            // Execute statement
            if ($statement->execute()) {
                echo "Signup successful!";
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
    <title>Signup Form</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Equipments</h2>
        <form method="POST" action="equipments.php">
    <div class="mb-3">
        <label for="equipmentName" class="form-label">Equipment Name</label>
        <input type="text" class="form-control" id="equipmentName" name="equipmentName" required>
    </div>
    <div class="mb-3">
        <label for="buyingPrice" class="form-label">Buying Price</label>
        <input type="text" class="form-control" id="buyingPrice" name="buyingPrice" required>
    </div>
    <div class="mb-3">
        <label for="AddedBy" class="form-label">Added By</label>
        <input type="text" class="form-control" id="AddedBy" name="AddedBy" required>
    </div>

    <button type="submit" class="btn btn-primary">Sumbit</button>
</form>

    <!-- Button to login -->
    <div class="mt-3">
        <a href="login.php" class="btn btn-secondary">Login</a>
    </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>