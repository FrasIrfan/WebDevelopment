<?php
include "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for the PaymentID
    $PaymentID = isset($_POST['PaymentID']) ? $_POST['PaymentID'] : null;

    if (!empty($PaymentID)) {
        // SQL to update payment status to 'confirmed'
        $sql = "UPDATE Payments SET PaymentStatus = 'verified' WHERE PaymentID = ?";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $statement->bind_param("i", $PaymentID);

            // Execute statement
            if ($statement->execute()) {
                header("Location:readPayments.php");
            } else {
                echo "Error: " . $statement->error;
            }

            // Close statement
            $statement->close();
        }
    } else {
        echo "Payment ID is required.";
    }
}
?>

