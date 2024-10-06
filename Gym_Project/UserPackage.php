<?php
require_once "database.php";
require_once "userPackageClass.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$userID = $_SESSION['userid'];

// Initialize database and user package objects
$db = new Database();
$userPackage = new UserPackage($db, $userID);

// Fetching current package
$currentPackage = $userPackage->getCurrentPackage();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Package Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="info.css">

</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Your Current Package</h3>
            </div>
            <div class="card-body">
                <?php if ($currentPackage) { ?>
                    <table class="table table-striped">
                        <tr>
                            <th>Package Name</th>
                            <td><?php echo htmlspecialchars($currentPackage['CurrentPackageName']); ?></td>
                        </tr>
                        <tr>
                            <th>Package Price</th>
                            <td>Rs <?php echo htmlspecialchars($currentPackage['CurrentPackagePrice']); ?></td>
                        </tr>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-warning" role="alert">
                        You have not selected a package yet.
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="mt-3">
            <a href="userDashboard.php" class="btn btn-primary">Go Back</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Close database connection
$db->close();
?>
