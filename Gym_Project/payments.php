<?php
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
// Start a session
session_start();

$sql = "SELECT ID , username FROM Users WHERE userType = 'member'";
$storedata = $mysqli->query($sql);

if (!$storedata) {
    echo "Error fetching data: " . $mysqli->error;
    exit();
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PaidBy = $_POST['PayerName'] ?? '';
    $PayerAmount = $_POST['PayerAmount'] ?? '';
    $PaymentMethod = $_POST['PaymentMethod'] ?? '';
    $PaymentRecievedBy = $_POST['PaymentRecievedBy'] ?? '';
    // $PaymentProof = $_POST['PaymentProof'] ?? '';

    //check if file is uploaded
    if(isset($_FILES['PaymentProof']) && $_FILES['PaymentProof']['error']==0){
        $targetDirectory= "/opt/lampp/htdocs/Gym_Project/fileUploads/";
        $fileName = basename($_FILES['PaymentProof']['name']);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
        //allow certain formats
        $allowTypes = array('jpg','png', 'jpeg', 'pdf');
        if (in_array($fileType,$allowTypes)){
            if (move_uploaded_file($_FILES['PaymentProof']['tmp_name'],$targetFilePath)){
                $PayerProof = $targetFilePath;
            } else {
                echo "There is a error uploading your file.";
                exit();
            }
        } else {
            echo "Only JPG , JPEG , PNG , and PDF files are allowed to upload";
            exit();
        }

    }else {
        echo "Please upload a file.";
        exit();
    }



    // SQL to insert data
    $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy) VALUES (?, ?, ?, ?)";
    // Prepare statement
    $statement = $mysqli->prepare($sql);
    // Check if the statement is prepared
    if ($statement === false) {
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        // Bind parameters
        $statement->bind_param(
            "sisss",
            $PaidBy,
            $PayerAmount,
            $PaymentMethod,
            $PaymentRecievedBy,
            $targetFilePath
        );

        if ($statement->execute()) {
            echo "<div class='alert alert-success' role='alert'>";
            echo "Payment added successfully";
            echo "</div>";
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
        <form method="POST" action="payments.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="PayerName" class="form-label">Select User</label>
                <select class="form-control" id="PayerName" name="PayerName" required>
                    <?php
                    if ($storedata->num_rows > 0) {
                        while ($row = $storedata->fetch_assoc()) {
                    ?> <option value=" <?= $row['ID']; ?>">
                                <?= $row['username']; ?>
                            </option>
                    <?php
                        }
                    }
                    ?>

                </select>
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
        <div class="mt-3">
            <a href="readPayments.php" class="btn btn-primary">View All Payments</a>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>