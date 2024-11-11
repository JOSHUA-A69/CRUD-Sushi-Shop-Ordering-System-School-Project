<?php
require_once '../config/database.php';

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Method to create a new admin
    public function create($name, $email, $username, $password, $role, $contactNumber) {
        $sql = "INSERT INTO administrators (name, email, username, password, role, contactNumber) 
                VALUES (:name, :email, :username, :password, :role, :contactNumber)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':contactNumber', $contactNumber);

        return $stmt->execute();
    }

    // Method to log in an admin
    public function login($username, $password) {
        $sql = "SELECT * FROM administrators WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;
    }

    // Method to find an admin by ID
    public function findById($adminID) {
        $sql = "SELECT * FROM administrators WHERE adminID = :adminID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':adminID', $adminID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to update an admin profile
    public function update($adminID, $data) {
        $sql = "UPDATE administrators SET name = :name, email = :email, username = :username, role = :role, contactNumber = :contactNumber 
                WHERE adminID = :adminID";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':contactNumber', $data['contactNumber']);
        $stmt->bindParam(':adminID', $adminID);

        return $stmt->execute();
    }
}
