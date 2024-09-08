<?php
// Database.php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "GYM";
    private $mysqli;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->mysqli->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Unable to prepare statement: " . $this->mysqli->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result === false) {
            return $stmt->affected_rows;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function close()
    {
        $this->mysqli->close();
    }

    public function getLastInsertId()
    {
        return $this->mysqli->insert_id;
    }
}
