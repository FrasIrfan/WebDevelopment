<?php
require_once 'database.php';

class EquipmentManager
{
    private $db;
    private $equipmentId;
    public $equipmentName;
    public $buyingPrice;

    public function __construct(Database $db, $equipmentId)
    {
        $this->db = $db;
        $this->equipmentId = $equipmentId;
    }

    public function fetchEquipmentDetails()
    {
        $query = "SELECT EquipmentName, BuyingPrice FROM Equipments WHERE EquipmentID = ?";
        $result = $this->db->query($query, [$this->equipmentId]);

        // Debugging: Check what result is being returned from the database query
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";

        if (!empty($result)) {
            $this->equipmentName = $result[0]['EquipmentName'];
            $this->buyingPrice = $result[0]['BuyingPrice'];
        } else {
            echo "No equipment found with this ID.";
        }
    }


    public function updateEquipment($equipmentName, $buyingPrice)
    {
        $sql = "UPDATE Equipments SET EquipmentName = ?, BuyingPrice = ? WHERE EquipmentID = ?";
        return $this->db->query($sql, [$equipmentName, $buyingPrice, $this->equipmentId]) !== false;
    }
}
