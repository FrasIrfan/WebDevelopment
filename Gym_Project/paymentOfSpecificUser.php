<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php'; // Database connection

// Start a session
session_start();
// Get the current user's ID from the session
$currentUserId = $_SESSION['userid'];
print_r($currentUserId);


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PaidBy = $currentUserId; // Automatically use the logged-in user's ID
    $PayerAmount = $_POST['PayerAmount'] ?? '';
    $PaymentMethod = $_POST['PaymentMethod'] ?? '';
    $PaymentRecievedBy = $_POST['PaymentRecievedBy'] ?? '';

    $targetFilePath = '';
    // Check if file is uploaded
    if (isset($_FILES['PaymentProof']) && $_FILES['PaymentProof']['error'] == 0) {
        $targetDirectory = "/opt/lampp/htdocs/Gym_Project/fileUploads/paymentProof/";
        $fileName = basename($_FILES['PaymentProof']['name']);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'pdf');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['PaymentProof']['tmp_name'], $targetFilePath)) {
                // File upload successful
            } else {
                echo "There was an error uploading your file.";
                exit();
            }
        } else {
            echo "Only JPG, JPEG, PNG, and PDF files are allowed.";
            exit();
        }
    } else {
        echo "Please upload a file.";
        exit();
    }

    // Check if user has already made a payment this month
    $sqlCheck = "
        SELECT * FROM Payments 
        WHERE PaidBy = ? 
        AND YEAR(CreatedAt) = YEAR(CURRENT_DATE())
        AND MONTH(CreatedAt) = MONTH(CURRENT_DATE())
    ";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $PaidBy); // Use the logged-in user's ID
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "You have already made a payment this month.";
        echo "</div>";
    } else {
        // SQL to insert data
        $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy, PaymentProof) VALUES (?, ?, ?, ?, ?)";

        // Prepare statement
        $statement = $mysqli->prepare($sql);

        // Check if the statement is prepared
        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters
            $statement->bind_param(
                "iisss", // Adjusted parameter types
                $PaidBy, // Use the logged-in user's ID
                $PayerAmount,
                $PaymentMethod,
                $PaymentRecievedBy,
                $targetFilePath
            );

            if ($statement->execute()) {
                echo "<div class='alert alert-success' role='alert'>";
                echo "Payment added successfully.";
                echo "</div>";
            } else {
                echo "Error adding payment: " . $mysqli->error;
            }

            // Close statement
            $statement->close();
        }
    }
}

// Close the database connection
$mysqli->close();
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
        <form method="POST" action="paymentOfSpecificUser.php" enctype="multipart/form-data">
            <!-- Removed the User dropdown -->
            <div class="mb-3">
                <label for="PayerAmount" class="form-label">Payer's Amount</label>
                <input type="number" class="form-control" id="PayerAmount" name="PayerAmount" required>
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
    
        <div class="mt-3">
            <a href="userDashboard.php" class="btn btn-primary">Go back</a>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>