<?php
 require_once '../config/database.php';

class Customer {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create($data) {
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['name'], $data['email'], $passwordHash);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if ($customer && password_verify($password, $customer['password'])) {
            return $customer;
        }
        return false;
    }

    public function findById($customerID) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($customerID, $data) {
        $stmt = $this->db->prepare("UPDATE customers SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $data['name'], $data['email'], $customerID);
        return $stmt->execute();
    }
}
