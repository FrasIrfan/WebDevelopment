<?php
// User.php

require_once 'database.php'; // Include your Database class

class User
{
    private $db;
    private $currentUserId;

    public function __construct($db, $currentUserId)
    {
        $this->db = $db;
        $this->currentUserId = $currentUserId;
    }

    public function addUser($fname, $lname, $phone, $email, $username, $password, $userType)
    {
        // Check if email is already used
        if ($this->isEmailUsed($email)) {
            return ['status' => 'error', 'message' => "Email already used! Use another email."];
        }

        // Check if phone number is already used
        if ($this->isPhoneUsed($phone)) {
            return ['status' => 'error', 'message' => "This phone number is already associated with an account! Use a different phone number."];
        }

        // Check if username is already used
        if ($this->isUsernameUsed($username)) {
            return ['status' => 'error', 'message' => "Username already exists! Use a different username."];
        }

        // Insert the user into the database
        $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, userType, CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$fname, $lname, $phone, $email, $username, $password, $userType, $this->currentUserId];
        $this->db->query($sql, $params);

        return ['status' => 'success', 'message' => "User added successfully!"];
    }

    private function isEmailUsed($email)
    {
        $sql = "SELECT ID FROM Users WHERE email = ?";
        $params = [$email];
        $result = $this->db->query($sql, $params);
        return count($result) > 0;
    }

    private function isPhoneUsed($phone)
    {
        $sql = "SELECT ID FROM Users WHERE phone = ?";
        $params = [$phone];
        $result = $this->db->query($sql, $params);
        return count($result) > 0;
    }

    private function isUsernameUsed($username)
    {
        $sql = "SELECT ID FROM Users WHERE username = ?";
        $params = [$username];
        $result = $this->db->query($sql, $params);
        return count($result) > 0;
    }
}
?>
