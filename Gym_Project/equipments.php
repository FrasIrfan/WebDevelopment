<?php
include "config.php";  // Ensure $mysqli is initialized in config.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $addedBy = isset($_POST['addedBy']) ? $_POST['addedBy'] : null;
    $equipmentName = isset($_POST['equipmentName']) ? $_POST['equipmentName'] : null;
    $buyingPrice = isset($_POST['buyingPrice']) ? $_POST['buyingPrice'] : null;

    // SQL to insert data
    $sql = "INSERT INTO Equipments (AddedBy, EquipmentName, BuyingPrice) VALUES (?, ?, ?)";

    // Prepare statement with mysqli
    $statement = $mysqli->prepare($sql);

    if ($statement === false) {
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        // Bind parameters and execute statement
        $statement->bind_param(
            "ssi",
            $addedBy,       // ENUM value (string)
            $equipmentName, // String
            $buyingPrice    // Integer
        );

        // Execute statement
        if ($statement->execute()) {
            echo "Equipment Added!";
        } else {
            echo "Error: " . $statement->error;
        }

        // Close statement
        $statement->close();
    }
} else {
    echo "Please fill out all required fields.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipments</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Add Equipments</h2>
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
                <label for="addedBy" class="form-label">Added By</label>
                <select name="addedBy" id="addedBy" class="form-control" required>
                    <option value="">Select a type</option>
                    <option value="owner">Owner</option>
                    <option value="trainer">Trainer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Equipment</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>