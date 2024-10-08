<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'database.php';

// Create a new Database instance
$db = new Database();

// SQL query to select first name, last name, and email from the registrations table
$sql = "SELECT EquipmentID, EquipmentName, BuyingPrice, CreatedAt FROM Equipments";

try {
    // Execute the query using the new Database class
    $equipments = $db->query($sql);
} catch (Exception $e) {
    // Handle any database errors
    $error = "Database error: " . $e->getMessage();
}

$db->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment List</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">

</head>

<body>
    <?php include('nav.php'); ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Equipment List</h2>
            <div>
                <a href="equipments.php" class="btn btn-info">Add Equipment</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>


        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php } elseif (empty($equipments)) { ?>
            <div class="alert alert-warning" role="alert">
                No Equipment found.
            </div>
        <?php } else { ?>
            <!-- Create a table to display the user list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Equipment Name</th>
                        <th>Buying Price</th>
                        <th>Added at</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipments as $equipment) { ?>
                        <tr>
                            <!-- Output the first name, last name, and email of each user -->
                            <td><?= $equipment['EquipmentName'] ?></td>
                            <td><?= $equipment['BuyingPrice'] ?></td>
                            <td><?= $equipment['CreatedAt'] ?></td>
                            <td style="width:15%;">
                                <a href="editEquipment.php?id=<?= $equipment['EquipmentID'] ?>" class="btn btn-primary btn-sm mr-2">Edit</a>


                                <a href="deleteEquipment.php?id=<?= $equipment['EquipmentID'] ?>" class="btn btn-danger btn-sm ">Remove</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php
        }
        ?>


        <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>