<?php
require_once 'equipmentClass.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipmentName = isset($_POST['equipmentName']) ? $_POST['equipmentName'] : null;
    $buyingPrice = isset($_POST['buyingPrice']) ? $_POST['buyingPrice'] : null;

    if (!empty($equipmentName) && !empty($buyingPrice)) {
        $equipment = new Equipment();
        $message = $equipment->addEquipment($equipmentName, $buyingPrice);
    } else {
        $message = "Please fill out all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Equipment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Add Equipment</h2>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="equipments.php">
            <div class="mb-3">
                <label for="equipmentName" class="form-label">Equipment Name</label>
                <input type="text" class="form-control" id="equipmentName" name="equipmentName" required>
            </div>
            <div class="mb-3">
                <label for="buyingPrice" class="form-label">Buying Price</label>
                <input type="text" class="form-control" id="buyingPrice" name="buyingPrice" required>
            </div>
            <!-- Change the anchor to a submit button -->
            <button type="submit" class="btn btn-primary">Add Equipment</button>
        </form>

        <div class="mt-3">
            <a href="readEquipment.php" class="btn btn-secondary">See Equipment List</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>