<?php
require_once '../config/database.php';

class Order {
    private $db;
    
    public function __construct() {
        // Initialize a MySQLi connection
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Create a new order
    public function create($data) {
        $customerID = $this->db->real_escape_string($data['customerID']);
        $items = json_encode($data['items']); // Assuming items are an array that will be stored as a JSON string
        
        $query = "INSERT INTO Orders (customerID, items) VALUES ('$customerID', '$items')";
        
        if ($this->db->query($query)) {
            return "Order placed successfully!";
        } else {
            throw new Exception("Error: " . $this->db->error);
        }
    }

    // Get order details by order ID
    public function findById($orderID) {
        $orderID = $this->db->real_escape_string($orderID);
        
        $query = "SELECT * FROM Orders WHERE orderID = '$orderID'";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Return as an associative array
        } else {
            throw new Exception("Order not found.");
        }
    }

    // Get orders for a specific customer
    public function findByCustomerId($customerID) {
        $customerID = $this->db->real_escape_string($customerID);
        
        $query = "SELECT * FROM Orders WHERE customerID = '$customerID'";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row; // Add each order to the array
            }
            return $orders;
        } else {
            throw new Exception("No orders found for the specified customer.");
        }
    }
   
    public function findById($orderID) {
        $sql = "SELECT * FROM orders WHERE orderID = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            die("MySQLi Error: " . $this->db->error);
        }
    
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        
        $stmt->close(); // Close statement after execution
        return $order;
    }
    
    public function updateStatus($orderID, $status) {
        $sql = "UPDATE orders SET status = ? WHERE orderID = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            die("MySQLi Error: " . $this->db->error);
        }
    
        $stmt->bind_param("si", $status, $orderID);
        $success = $stmt->execute();
    
        $stmt->close(); // Close statement after execution
        return $success;
    }
    
}
?>
