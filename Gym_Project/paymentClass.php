<?php
require_once 'database.php';

class Payment
{
    private $db;
    private $currentUserId;

    public function __construct($currentUserId)
    {
        $this->db = new Database();
        $this->currentUserId = $currentUserId;
    }


    public function hasAlreadyPaid($userID)
    {
        $sql = "
        SELECT * FROM Payments
        WHERE PaidBy = ?
        AND YEAR(CreatedAt) = YEAR(CURRENT_DATE())
        AND MONTH(CreatedAt) = MONTH(CURRENT_DATE())
        ";
        $result = $this->db->query($sql, [$userID]);
        // return $result->num_rows > 0;
    }




    // Fetch user's package price and payment status
    public function getPackageDetails()
    {
        $sql = "
            SELECT P.PackagePrice, PAY.PaymentStatus 
            FROM UserPackage UP 
            JOIN Packages P ON UP.PackageID = P.PackageID 
            LEFT JOIN Payments PAY ON UP.UserID = PAY.PaidBy 
            WHERE UP.UserID = ? 
            ORDER BY PAY.CreatedAt DESC 
            LIMIT 1
        ";

        $params = [$this->currentUserId];
        $result = $this->db->query($sql, $params);
        return $result ? $result[0] : ['PackagePrice' => null, 'PaymentStatus' => 'No Payment Record'];
    }

    // Check if user has already made a payment this month
    public function hasMadePaymentThisMonth()
    {
        $sql = "
            SELECT * FROM Payments 
            WHERE PaidBy = ? 
            AND YEAR(CreatedAt) = YEAR(CURRENT_DATE()) 
            AND MONTH(CreatedAt) = MONTH(CURRENT_DATE())
        ";
        $params = [$this->currentUserId];
        $result = $this->db->query($sql, $params);
        return count($result) > 0;
    }

    // Add a new payment record
    public function addPayment($PayerAmount, $PaymentMethod, $PaymentRecievedBy, $PaymentProof)
    {
        $sql = "INSERT INTO Payments (PaidBy, PayerAmount, PaymentMethod, PaymentRecievedBy, PaymentProof) VALUES (?, ?, ?, ?, ?)";
        $params = [$this->currentUserId, $PayerAmount, $PaymentMethod, $PaymentRecievedBy, $PaymentProof];

        try {
            $this->db->query($sql, $params);
            return "Payment added successfully.";
        } catch (Exception $e) {
            return "Error adding payment: " . $e->getMessage();
        }
    }

    public function uploadPaymentProof($file)
    {
        if (isset($file['error']) && $file['error'] == 0) {
            $targetDirectory = "fileUploads/paymentProof/";
            $fileName = basename($file['name']);
            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowedTypes = array('jpg', 'png', 'jpeg', 'pdf');
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    return $targetFilePath;
                }
            }
        }
        return null;
    }

    // Handle file uploads
    public function handleFileUpload($file)
    {
        if ($file['error'] === 0) {
            $targetDirectory = "fileUploads/paymentProof/";
            $fileName = basename($file['name']);
            $targetFilePath = $targetDirectory . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowedTypes = ['jpg', 'png', 'jpeg', 'pdf'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    return $targetFilePath;
                } else {
                    throw new Exception("There was an error uploading your file.");
                }
            } else {
                throw new Exception("Only JPG, JPEG, PNG, and PDF files are allowed.");
            }
        } else {
            throw new Exception("Please upload a file.");
        }
    }
}
