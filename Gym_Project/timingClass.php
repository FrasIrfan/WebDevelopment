<?php
require_once 'database.php';

class Timing
{
    private $db;

    public function __construct()
    {
        // Initialize the Database connection
        $this->db = new Database();
    }

    public function updateTiming($Shifts, $startTime, $endTime)
    {
        try {
            $sql = "UPDATE Timings SET startTime = ?, endTime = ? WHERE Shifts = ?";
            $this->db->query($sql, [$startTime, $endTime, $Shifts]);
            return "Timing Updated!";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function __destruct()
    {
        // Close database connection when object is destroyed
        $this->db->close();
    }
}
