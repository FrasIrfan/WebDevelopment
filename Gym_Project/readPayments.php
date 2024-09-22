<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the new Database class
require_once 'database.php';

// Create a new Database instance
$db = new Database();

// SQL query to select payment details from the Payments table
$sql = "
    SELECT Payments.PaymentID AS PaymentID, Users.username AS PaidBy, Payments.PayerAmount, Payments.PaymentMethod, Payments.PaymentRecievedBy, Payments.PaymentStatus, Payments.PaymentProof, Payments.CreatedAt
    FROM Payments
    JOIN Users ON Payments.PaidBy = Users.ID
";

try {
    // Execute the query and fetch results as an associative array
    $payments = $db->query($sql);
} catch (Exception $e) {
    // Handle any database errors
    $error = "Database error: " . $e->getMessage();
}

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Payments</title>
    <!-- Include Bootstrap CSS for responsive and styled UI components -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Payments List</h2>
            <div>
                <!-- <a href="payments.php" class="btn btn-info">Make a Payment</a> -->
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php }
        elseif (empty($payments)) { ?>
            <div class="alert alert-warning" role="alert">
                No Payments found.
            </div>
        <?php }
        else { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Paid By</th>
                        <th>Payer Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Received By</th>
                        <th>Payment Proof</th>
                        <th>Created At</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment) { ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['PaidBy']) ?></td>
                            <td><?= htmlspecialchars($payment['PayerAmount']) ?></td>
                            <td><?= htmlspecialchars($payment['PaymentMethod']) ?></td>
                            <td><?= htmlspecialchars($payment['PaymentRecievedBy']) ?></td>

                            <td>
                                <?php if (!empty($payment['PaymentProof'])) { ?>
                                    <img src="<?= htmlspecialchars($payment['PaymentProof']) ?>" style="max-width: 100px; height: auto;">
                                <?php } else { ?>
                                    No Proof Provided
                                <?php } ?>
                            </td>

                            <td><?= htmlspecialchars($payment['CreatedAt']) ?></td>
                            <td>
                                <?= htmlspecialchars($payment['PaymentStatus']) ?>
                                <?php if ($payment['PaymentStatus'] != 'verified') { ?>
                                    <form method="POST" action="paymentStatus.php" style="display:inline;">
                                        <input type="hidden" name="PaymentID" value="<?= htmlspecialchars($payment['PaymentID']) ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Confirm</button>
                                    </form>
                                <?php } else { ?>
                                    Confirmed
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
