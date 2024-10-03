<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'paymentClass.php'; // Include the Payment class
require_once 'database.php'; // Include the Database config

// Start a session
session_start();

// Get the current user's ID from the session
$currentUserId = $_SESSION['userid'];
// print_r($currentUserId);

// Instantiate the Payment class
$payment = new Payment($currentUserId);

// Fetch the user's current package price and payment status
$packageDetails = $payment->getPackageDetails();
$packagePrice = $packageDetails['PackagePrice'] ?? null;
$paymentStatus = $packageDetails['PaymentStatus'] ?? 'No Payment Record';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PaymentMethod = $_POST['PaymentMethod'] ?? '';
    $PaymentRecievedBy = $_POST['PaymentRecievedBy'] ?? '';

    // Upload file if payment method is online
    $targetFilePath = '';
    if ($PaymentMethod === 'online') {
        try {
            $targetFilePath = $payment->uploadPaymentProof($_FILES['PaymentProof']);
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            exit();
        }
    }

    // Check if the user has already made a payment this month
    if ($payment->hasMadePaymentThisMonth()) {
        echo "<div class='alert alert-danger'>You have already made a payment this month.</div>";
    } else {
        // Add payment to the database
        $PaymentRecievedBy = ($PaymentMethod === 'cash') ? $_POST['PaymentRecievedBy'] : null;
        $message = $payment->addPayment($packagePrice, $PaymentMethod, $PaymentRecievedBy, $targetFilePath);
        echo "<div class='alert alert-success'>{$message}</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Payments</h2>
        <?php if ($paymentStatus !== 'No Payment Record'): ?>
            <div class="alert alert-info" role="alert">
                Current Payment : <?php echo htmlspecialchars($paymentStatus); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                No payment record found for the current month.
            </div>
        <?php endif; ?>

        <form method="POST" action="paymentOfSpecificUser.php" enctype="multipart/form-data">
            <!-- Payer Amount (Auto-filled and read-only) -->
            <div class="mb-3">
                <label for="PayerAmount" class="form-label">Payer's Amount</label>
                <input type="number" class="form-control" id="PayerAmount" name="PayerAmount" value="<?php echo htmlspecialchars($packagePrice); ?>" readonly>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label for="PaymentMethod" class="form-label">Payment Method</label>
                <select class="form-select" aria-label="Default select example" id="PaymentMethod" name="PaymentMethod" required onchange="toggleFields()">
                    <option value="" selected disabled>Select a payment method</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>

            <!-- Payment Received By (Visible for cash only) -->
            <div class="mb-3" id="paymentReceivedBy" style="display: none;">
                <label for="PaymentRecievedBy" class="form-label">Payment Received By</label>
                <select class="form-select" aria-label="Default select example" id="PaymentRecievedBy" name="PaymentRecievedBy">
                    <option value="" selected disabled>Select who received the payment</option>
                    <option value="Owner">Owner</option>
                    <option value="Trainer">Trainer</option>
                </select>
            </div>

            <!-- Payment Proof (Visible for online only) -->
            <div class="mb-3" id="PaymentProof" style="display: none;">
                <label for="PaymentProof" class="form-label">Payment Proof</label>
                <input type="file" class="form-control" id="PaymentProof" name="PaymentProof">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="mt-3">
            <a href="userDashboard.php" class="btn btn-primary">Go back</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JavaScript to toggle fields -->
    <script>
        function toggleFields() {
            const paymentMethod = document.getElementById("PaymentMethod").value;
            const paymentReceivedBy = document.getElementById("paymentReceivedBy");
            const PaymentProof = document.getElementById("PaymentProof");

            if (paymentMethod === "cash") {
                paymentReceivedBy.style.display = "block";
                PaymentProof.style.display = "none";
            } else if (paymentMethod === "online") {
                paymentReceivedBy.style.display = "none";
                PaymentProof.style.display = "block";
            } else {
                paymentReceivedBy.style.display = "none";
                PaymentProof.style.display = "none";
            }
        }
    </script>
</body>

</html>
