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
    // Terminate the script and display the connection error
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the equipment ID is provided in the URL
if (isset($_GET['id'])) {
    $equipmentId = $_GET['id'];

    // Check if the form is submitted to update equipment details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipmentName = $_POST['equipment_name'];
        $buyingPrice = $_POST['buying_price'];

        // Prepare and execute the SQL query to update the equipment
        $stmt = $mysqli->prepare("UPDATE Equipments SET EquipmentName = ?, BuyingPrice = ? WHERE EquipmentID = ?");
        $stmt->bind_param("sdi", $equipmentName, $buyingPrice, $equipmentId);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Equipment updated successfully.</div>";
            header('Location: readEquipment.php');
        } else {
            echo "<div class='alert alert-danger'>Failed to update equipment. Please try again.</div>";
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
    <title>Edit Equipment</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Equipment</h2>
        <form method="post">
            <div class="form-group">
                <label for="equipmentName">Equipment Name</label>
                <input type="text" class="form-control" id="equipmentName" name="equipment_name" value="<?= htmlspecialchars($equipmentName) ?>" required>
            </div>
            <div class="form-group">
                <label for="buyingPrice">Buying Price</label>
                <input type="number" class="form-control" id="buyingPrice" name="buying_price" value="<?= htmlspecialchars($buyingPrice) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Equipment</button>
            <a href="readEquipment.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Close the database connection to free up resources
$mysqli->close();
?>
