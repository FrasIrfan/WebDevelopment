<?php
class UserPackage
{
    private $db;
    private $userID;

    public function __construct(Database $db, $userID)
    {
        $this->db = $db;
        $this->userID = $userID;
    }

    public function getCurrentPackage()
    {
        $sql = "
            SELECT P1.PackageName AS CurrentPackageName, P1.PackagePrice AS CurrentPackagePrice
            FROM UserPackage UP
            JOIN Packages P1 ON UP.PackageID = P1.PackageID
            WHERE UP.UserID = ?
        ";

        $result = $this->db->query($sql, [$this->userID]);

        return $result ? $result[0] : null; // Return the first row or null if no package
    }
}
?>
