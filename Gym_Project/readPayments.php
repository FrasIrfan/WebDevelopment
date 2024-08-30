<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database configuration file to establish a database connection
include 'config.php';
session_start();

// Check if there is a connection error with the database
if ($mysqli->connect_error) {
    // Terminate the script and display the connection error
    die("Connection failed: " . $mysqli->connect_error);
}

// SQL query to select payment details from the Payments table
$sql = "
    SELECT Payments.PaymentID AS PaymentID, Users.username AS PaidBy, Payments.PayerAmount, Payments.PaymentMethod, Payments.PaymentRecievedBy,Payments.PaymentStatus, Payments.PaymentProof, Payments.CreatedAt
    FROM Payments
    JOIN Users ON Payments.PaidBy = Users.ID
";
// Execute the SQL query and store the result in $result
$result = $mysqli->query($sql);
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
                <a href="payments.php" class="btn btn-info">Make a Payment</a>
                <a href="adminDashboard.php" class="btn btn-info">Go Back</a>
            </div>
        </div>

        <?php if ($result->num_rows > 0) { // Check if the query returned any rows 
        ?>
            <!-- Create a table to display the payment list with Bootstrap classes for styling -->
            <table class="table table-bordered">
                <thead>
                    <!-- Define table headers -->
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
                    <?php
                    // Loop through each row in the result set
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <!-- Output the payment details -->
                            <td><?= $row['PaidBy'] ?></td>
                            <td><?= $row['PayerAmount'] ?></td>
                            <td><?= $row['PaymentMethod'] ?></td>
                            <td><?= $row['PaymentRecievedBy'] ?></td>

                            <td>
                                <?php if (!empty($row['PaymentProof'])) { ?>
                                    <!-- Display the payment proof image -->
                                    <img src="<?= $row['PaymentProof'] ?>" style="max-width: 100px; height: auto;">
                                <?php } else { ?>
                                    echo "No Proof Provided";
                                <?php } ?>
                            </td>

                            <td><?= $row['CreatedAt'] ?></td>
                            <td><?= $row['PaymentStatus'] ?>
                            
                                <?php if ($row['PaymentStatus'] != 'verified') { ?>
                                    <form method="POST" action="paymentStatus.php">
                                        <input type="hidden" name="PaymentID" value="<?= $row['PaymentID'] ?>">
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
        <?php } else { // If no rows were returned, display a message indicating no payments found 
        ?>
            <div class="alert alert-warning" role="alert">
                No Payments found.
            </div>
        <?php } ?>
    </div>

    <!-- Include Bootstrap's JavaScript and jQuery libraries for interactive components -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
// Close the database connection to free up resources
$mysqli->close();
?>