<?php
require_once '../config/database.php';

class Admin {

    private $db;

    public function __construct() {
        // Initialize a MySQLi connection
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Create a new admin
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO admins (name, email, username, password, role, contactNumber) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['name'], $data['email'], $data['username'], $data['password'], $data['role'], $data['contactNumber']);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Admin login
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;
    }

    // Find an admin by ID
    public function findById($adminID) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->bind_param("i", $adminID);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        return $admin;
    }

    // Update admin profile
    public function update($adminID, $data) {
        $stmt = $this->db->prepare("UPDATE admins SET name = ?, email = ?, username = ?, role = ?, contactNumber = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $data['name'], $data['email'], $data['username'], $data['role'], $data['contactNumber'], $adminID);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Close the MySQLi connection
    public function __destruct() {
        $this->db->close();
    }
}
?>
