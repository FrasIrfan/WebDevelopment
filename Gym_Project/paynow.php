<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
// Start a session
session_start();

class PaymentProcessor
{
    private $db;
    private $currentUserId;
    private $packagePrice;
    private $paymentStatus;

    public function __construct($db)
    {
        $this->db = $db;
        $this->currentUserId = $_SESSION['userid'];
        $this->fetchPackageDetails();
    }

    private function fetchPackageDetails()
    {
        $packageDetailsSQL = "
            SELECT P.PackagePrice, PAY.PaymentStatus 
            FROM UserPackage UP 
            JOIN Packages P ON UP.PackageID = P.PackageID 
            LEFT JOIN Payments PAY ON UP.UserID = PAY.PaidBy 
            WHERE UP.UserID = ?
            ORDER BY PAY.CreatedAt DESC 
            LIMIT 1
        ";

        $stmt = $this->db->prepare($packageDetailsSQL);
        $stmt->bind_param("i", $this->currentUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
        $this->packagePrice = $details['PackagePrice'] ?? null;
        $this->paymentStatus = $details['PaymentStatus'] ?? 'No Payment Record';

        // Redirect to dashboard if payment is verified
        if ($this->paymentStatus === 'verified') {
            header("Location: userDashboard.php");
            exit();
        }
    }

    public function getPackagePrice()
    {
        return $this->packagePrice;
    }

    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    public function handleFormSubmission()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $PaidBy = $this->currentUserId;
            $PayerAmount = $this->packagePrice;
            $PaymentMethod = $_POST['PaymentMethod'] ?? '';
            $PaymentRecievedBy = $_POST['PaymentRecievedBy'] ?? '';

            $targetFilePath = $this->handleFileUpload($PaymentMethod);

            if ($this->checkIfPaymentExists($PaidBy)) {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "You have already made a payment this month.";
                echo "</div>";
            } else {
                $this->insertPayment($PaidBy, $PayerAmount, $PaymentMethod, $PaymentRecievedBy, $targetFilePath);
            }
        }
    }

    private function handleFileUpload($PaymentMethod)
    {
        if ($PaymentMethod === 'online' && isset($_FILES['PaymentProof']) && $_FILES['PaymentProof']['error'] == 0) {
            $targetDirectory = "fileUploads/paymentProof/";
            $fileName = basename($_FILES['PaymentProof']['name']);
            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'pdf');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['PaymentProof']['tmp_name'], $targetFilePath)) {
                    return $targetFilePath;
                } else {
                    echo "There was an error uploading your file.";
                    exit();
                }
            } else {
                echo "Only JPG, JPEG, PNG, and PDF files are allowed.";
                exit();
            }
        }

        return null;
    }

    private function checkIfPaymentExists($PaidBy)
    {
        $sqlCheck = "
            SELECT * FROM Payments 
            WHERE PaidBy = ? 
            AND YEAR(CreatedAt) = YEAR(CURRENT_DATE())
            AND MONTH(CreatedAt) = MONTH(CURRENT_DATE())
        ";

        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $PaidBy);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        return $resultCheck->num_rows > 0;
    }

    private function insertPayment($PaidBy, $PayerAmount, $PaymentMethod, $PaymentRecievedBy, $targetFilePath)
    {
        $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy, PaymentProof) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            echo "Error preparing statement: " . $this->db->error;
            return;
        }

        $PaymentRecievedBy = ($PaymentMethod === 'cash') ? $_POST['PaymentRecievedBy'] : null;

        $stmt->bind_param(
            "iisss",
            $PaidBy,
            $PayerAmount,
            $PaymentMethod,
            $PaymentRecievedBy,
            $targetFilePath
        );

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>";
            echo "Payment added successfully.";
            echo "</div>";
        } else {
            echo "Error adding payment: " . $this->db->error;
        }

        $stmt->close();
    }
}

// Initialize the PaymentProcessor class and handle form submission
$paymentProcessor = new PaymentProcessor($mysqli);
$paymentProcessor->handleFormSubmission();

// Close the database connection
$mysqli->close();
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
        <h2>Pay Now to Get Access to your Account</h2>

        <?php if ($paymentProcessor->getPaymentStatus() === 'pending'): ?>
            <div class="alert alert-warning" role="alert">
                Please wait to get access to your account until your payment is verified.
            </div>
        <?php elseif ($paymentProcessor->getPaymentStatus() === 'No Payment Record'): ?>
            <div class="alert alert-warning" role="alert">
                No payment record found for the current month.
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Current Payment Status: <?php echo htmlspecialchars($paymentProcessor->getPaymentStatus()); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="paynow.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="PayerAmount" class="form-label">Payer's Amount</label>
                <input type="number" class="form-control" id="PayerAmount" name="PayerAmount" value="<?php echo htmlspecialchars($paymentProcessor->getPackagePrice()); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="PaymentMethod" class="form-label">Payment Method</label>
                <select class="form-select" aria-label="Default select example" id="PaymentMethod" name="PaymentMethod" required onchange="toggleFields()">
                    <option value="" selected disabled>Select a payment method</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>

            <div class="mb-3" id="paymentReceivedBy" style="display: none;">
                <label for="PaymentRecievedBy" class="form-label">Payment Received By</label>
                <select class="form-select" aria-label="Default select example" id="PaymentRecievedBy" name="PaymentRecievedBy">
                    <option value="" selected disabled>Select who received the payment</option>
                    <option value="Owner">Owner</option>
                    <option value="Trainer">Trainer</option>
                </select>
            </div>

            <div class="mb-3" id="PaymentProof" style="display: none;">
                <label for="PaymentProof" class="form-label">Payment Proof</label>
                <input type="file" class="form-control" id="PaymentProof" name="PaymentProof">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="mt-3">
            <a href="index.php" class="btn btn-primary">Go Back</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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