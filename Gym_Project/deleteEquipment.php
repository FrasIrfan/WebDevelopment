<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file
include 'config.php';
session_start();

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the equipment ID is provided in the URL
if (isset($_GET['id'])) {
    $equipmentId = $_GET['id'];

    // Check if the form is submitted to delete equipment
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        // Prepare and execute the SQL query to delete the equipment
        $stmt = $mysqli->prepare("DELETE FROM Equipments WHERE EquipmentID = ?");
        $stmt->bind_param("i", $equipmentId);
        if ($stmt->execute()) {
            // Redirect to the equipment list page after successful deletion
            header('Location: readEquipments.php');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to delete equipment. Please try again.</div>";
        }
        $stmt->close();
    }

    // Prepare and execute the SQL query to fetch the current equipment details
    $stmt = $mysqli->prepare("SELECT EquipmentName, BuyingPrice FROM Equipments WHERE EquipmentID = ?");
    $stmt->bind_param("i", $equipmentId);
    $stmt->execute();
    $stmt->bind_result($equipmentName, $buyingPrice);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>No equipment ID provided.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Equipment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete Equipment</h2>
        <p>Are you sure you want to delete the following equipment?</p>
        <ul>
            <li><strong>Equipment Name:</strong> <?= $equipmentName ?></li>
            <li><strong>Buying Price:</strong> <?= $buyingPrice ?></li>
        </ul>
        <form method="post">
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this equipment?');">Delete Equipment</button>
            <a href="readEquipment.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection to free up resources
$mysqli->close();
?>
