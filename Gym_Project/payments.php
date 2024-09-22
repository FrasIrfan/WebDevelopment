<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'database.php'; // Include the Database class

// Start a session
session_start();

class PaymentProcessor {
    private $db;
    private $currentUserId;
    private $userType;

    public function __construct($db) {
        $this->db = $db; // Use the Database instance
        $this->currentUserId = $_SESSION['userid'];
        $this->userType = $this->fetchUserType();
    }

    private function fetchUserType() {
        $sql = "SELECT UserType FROM Users WHERE ID = ?";
        $result = $this->db->query($sql, [$this->currentUserId]);
        return !empty($result) ? $result[0]['UserType'] : null;
    }

    public function fetchMembers() {
        $sql = "SELECT ID, username FROM Users WHERE userType = 'member'";
        return $this->db->query($sql);
    }

    public function processPayment($data) {
        $targetFilePath = $this->handleFileUpload($data['PaymentProof']);

        if ($this->checkDuplicatePayment($data['PaidBy'])) {
            return "<div class='alert alert-danger' role='alert'>You have already made a payment this month.</div>";
        }

        $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy, PaymentProof, PaymentStatus) VALUES (?, ?, ?, ?, ?, 'pending')";
        $this->db->query($sql, [
            $data['PaidBy'], 
            $data['PayerAmount'], 
            $data['PaymentMethod'], 
            $this->userType, 
            $targetFilePath
        ]);

        return "<div class='alert alert-success' role='alert'>Payment added successfully.</div>";
    }

    private function handleFileUpload($file) {
        if ($file['error'] !== 0) {
            throw new Exception("Please upload a valid file.");
        }

        $targetDirectory = "fileUploads/paymentProof/";
        $fileName = basename($file['name']);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = ['jpg', 'png', 'jpeg', 'pdf'];

        if (!in_array($fileType, $allowTypes)) {
            throw new Exception("Only JPG, JPEG, PNG, and PDF files are allowed.");
        }

        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            throw new Exception("There was an error uploading your file.");
        }

        return $targetFilePath;
    }

    private function checkDuplicatePayment($paidBy) {
        $sql = "SELECT * FROM Payments WHERE PaidBy = ? AND YEAR(CreatedAt) = YEAR(CURRENT_DATE()) AND MONTH(CreatedAt) = MONTH(CURRENT_DATE())";
        $result = $this->db->query($sql, [$paidBy]);
        return !empty($result);
    }
}

// Main execution
$database = new Database(); // Create a new Database instance
$paymentProcessor = new PaymentProcessor($database);
$members = $paymentProcessor->fetchMembers();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentData = [
        'PaidBy' => $_POST['PayerName'] ?? '',
        'PayerAmount' => $_POST['PayerAmount'] ?? '',
        'PaymentMethod' => $_POST['PaymentMethod'] ?? '',
        'PaymentProof' => $_FILES['PaymentProof'] ?? null,
    ];
    
    try {
        $message = $paymentProcessor->processPayment($paymentData);
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Payments</h2>
        <?php if ($message) echo $message; ?>
        <form method="POST" action="payments.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="PayerName" class="form-label">Select User</label>
                <select class="form-control" id="PayerName" name="PayerName" required>
                    <?php foreach ($members as $row): ?>
                        <option value="<?= htmlspecialchars($row['ID']); ?>"><?= htmlspecialchars($row['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="PayerAmount" class="form-label">Payer's Amount</label>
                <input type="number" class="form-control" id="PayerAmount" name="PayerAmount" required>
            </div>
            <div class="mb-3">
                <label for="PaymentMethod" class="form-label">Payment Method</label>
                <select class="form-select" id="PaymentMethod" name="PaymentMethod" required>
                    <option value="" selected disabled>Open this select menu</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="PaymentProof" class="form-label">Payment Proof</label>
                <input type="file" class="form-control" id="PaymentProof" name="PaymentProof" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="mt-3">
            <a href="readPayments.php" class="btn btn-primary">View All Payments</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
