<?php
require_once 'database.php';

class Package {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Method to update package price
    public function updatePackage($packageName, $packagePrice) {
        if (!empty($packageName) && !empty($packagePrice)) {
            try {
                // SQL query to update data
                $sql = "UPDATE Packages SET PackagePrice = ? WHERE PackageName = ?";

                // Execute query using Database class's query method
                $params = [$packagePrice, $packageName];
                $this->db->query($sql, $params);

                return "Package Updated!";
            } catch (Exception $e) {
                return "Error: " . $e->getMessage();
            }
        } else {
            return "Please fill out all required fields.";
        }
    }

    // Optionally, you can add other package-related methods here, like retrieving or deleting packages
    public function getPackages() {
        try {
            $sql = "SELECT * FROM Packages";
            return $this->db->query($sql);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

?>
