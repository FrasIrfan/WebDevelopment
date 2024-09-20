<?php
require_once 'database.php';

class Equipment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addEquipment($equipmentName, $buyingPrice) {
        try {
            $sql = "INSERT INTO Equipments (EquipmentName, BuyingPrice) VALUES (?, ?)";
            $this->db->query($sql, [$equipmentName, $buyingPrice]);

            return "Equipment Added!";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
