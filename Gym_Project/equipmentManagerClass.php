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
        $sql = "SELECT EquipmentName, BuyingPrice FROM Equipments WHERE EquipmentID = ?";
        $result = $this->db->query($sql, [$this->equipmentId]);

        if (!empty($result)) {
            $this->equipmentName = $result[0]['EquipmentName'];
            $this->buyingPrice = $result[0]['BuyingPrice'];
        }
    }

    public function updateEquipment($equipmentName, $buyingPrice)
    {
        $sql = "UPDATE Equipments SET EquipmentName = ?, BuyingPrice = ? WHERE EquipmentID = ?";
        return $this->db->query($sql, [$equipmentName, $buyingPrice, $this->equipmentId]) !== false;
    }
}
