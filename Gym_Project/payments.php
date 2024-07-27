<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
// Start a session
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PaidBy = $_POST['PaidBy'] ?? '';
    $PayerAmount = $_POST['PayerAmount'] ?? '';
    $PaymentMethod = $_POST['PaymentMethod'] ?? '';
    $PaymentRecievedBy = $_POST['PaymentRecievedBy'] ?? '';
    $PaymentProof = $_POST['PaymentProof'] ?? '';

    // SQL to insert data
    $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy, PaymentProof) VALUES (?, ?, ?, ?, ?)";
    // Prepare statement
    $statement = $mysqli->prepare($sql);
    // Check if the statement is prepared
    if ($statement === false) {
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        // Bind parameters
        // paid by -- int
        // payeramount -- int
        // paymentmethod -- enum (string)
        // Payment recieved by -- enum (string)
        // PaymentProof -- string
        $statement->bind_param("iisss", $PaidBy, $PayerAmount, $PaymentMethod, $PaymentRecievedBy, $PaymentProof);

        if ($statement->execute()) {
            echo "Payment added successfully";
        } else {
            echo "Error adding payment: " . $mysqli->error;
        }
        // close statement
        $statement->close();
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
        <form method="POST" action="payments.php">
            <div class="mb-3">
                <label for="PayerName" class="form-label">Payer's Name</label>
                <input type="text" class="form-control" id="PayerName" name="PayerName" required>
            </div>
            <div class="mb-3">
                <label for="PayerAmount" class="form-label">Payer's Amount</label>
                <input type="number" class="form-control" id="PayerAmount" name="PayerAmount" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="PaymentMethod" class="form-label">Payment Method</label>
                <select class="form-select" aria-label="Default select example" id="PaymentMethod" name="PaymentMethod" required>
                    <option value="" selected disabled>Open this select menu</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="PaymentRecievedBy" class="form-label">Payment Received By</label>
                <select class="form-select" aria-label="Default select example" id="PaymentRecievedBy" name="PaymentRecievedBy" required>
                    <option value="" selected disabled>Open this select menu</option>
                    <option value="Owner">Owner</option>
                    <option value="Trainer">Trainer</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="PaymentProof" class="form-label">Payment Proof</label>
                <input type="file" class="form-control" id="PaymentProof" name="PaymentProof">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>