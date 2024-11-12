<?php
require_once '../config/database.php';

class Customer {
    
    private $db;

    public function __construct() {
        // Initialize a MySQLi connection
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Create a new customer or admin
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO customers (firstName, middleInitial, lastName, email, phoneNumber, city, street, houseNumber, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssssss",
            $data['firstName'],
            $data['middleInitial'],
            $data['lastName'],
            $data['email'],
            $data['phoneNumber'],
            $data['city'],
            $data['street'],
            $data['houseNumber'],
            $data['password'],
            $data['role']
        );
        
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Customer login
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        if ($customer && password_verify($password, $customer['password'])) {
            return $customer;
        }

        return false;
    }

    // Find a customer by ID
    public function findById($customerID) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customerID);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $stmt->close();

        return $customer;
    }

    // Update a customer profile
    public function update($customerID, $data) {
        $stmt = $this->db->prepare("UPDATE customers SET firstName = ?, middleInitial = ?, lastName = ?, email = ?, phoneNumber = ?, city = ?, street = ?, houseNumber = ? WHERE id = ?");
        $stmt->bind_param(
            "ssssssssi",
            $data['firstName'],
            $data['middleInitial'],
            $data['lastName'],
            $data['email'],
            $data['phoneNumber'],
            $data['city'],
            $data['street'],
            $data['houseNumber'],
            $customerID
        );
        
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function delete($customerID) {
        $sql = "DELETE FROM customers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerID);
        return $stmt->execute();
    }
    

    // Close the MySQLi connection
    public function __destruct() {
        $this->db->close();
    }
}
?>
