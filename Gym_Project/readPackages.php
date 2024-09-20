<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';

$db = new Database();

// SQL query to select package details
$sql = "SELECT PackageName, PackagePrice, CreatedAt FROM Packages";

try {
    // Execute the query using the new Database class
    $result = $db->query($sql); // Assuming this returns an array
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
    <title>Read Packages</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Packages List</h2>
            <div>
                <a href="updatePackage.php" class="btn btn-info">Update Package</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } elseif (count($result) > 0) { ?>
            <!-- Create a table to display the package list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
                    <tr>
                        <th>Package Name</th>
                        <th>Package Price</th>
                        <th>Updated at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $record) { ?>
                        <tr>
                            <!-- Output the package name, price, and created at date -->
                            <td><?= htmlspecialchars($record['PackageName']) ?></td>
                            <td><?= htmlspecialchars($record['PackagePrice']) ?></td>
                            <td><?= htmlspecialchars($record['CreatedAt']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning" role="alert">
                No Packages found.
            </div>
        <?php } ?>

        <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
</body>

</html>
