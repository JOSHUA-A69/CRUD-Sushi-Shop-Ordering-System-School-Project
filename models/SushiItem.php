<?php
require_once '../config/database.php';

class SushiItem {
    private $db;

    public function __construct() {
        // Initialize a MySQLi connection
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // Add a new Sushi item
    public function create($data) {
        $itemName = $this->db->real_escape_string($data['itemName']);
        $description = $this->db->real_escape_string($data['description']);
        $price = $this->db->real_escape_string($data['price']);
        $availabilityStatus = $this->db->real_escape_string($data['availabilityStatus']);
        $category = $this->db->real_escape_string($data['category']);
        $ingredients = $this->db->real_escape_string($data['ingredients']);

        $query = "INSERT INTO Sushi_Item (itemName, description, price, availabilityStatus, category, ingredients) 
                  VALUES ('$itemName', '$description', '$price', '$availabilityStatus', '$category', '$ingredients')";
        
        if ($this->db->query($query)) {
            return "Sushi item added successfully!";
        } else {
            throw new Exception("Error: " . $this->db->error);
        }
    }

    // Retrieve all Sushi items
    public function getAll() {
        $query = "SELECT * FROM Sushi_Item";
        $result = $this->db->query($query);

        $items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    // Delete a Sushi item by ID
    public function delete($itemID) {
        $itemID = $this->db->real_escape_string($itemID);
        $query = "DELETE FROM Sushi_Item WHERE itemID = '$itemID'";

        if ($this->db->query($query)) {
            return "Sushi item deleted successfully!";
        } else {
            throw new Exception("Error: " . $this->db->error);
        }
    }

    // Update a Sushi item (partial updates)
    public function update($itemID, $data) {
        $itemID = $this->db->real_escape_string($itemID);
        $fields = [];

        foreach ($data as $key => $value) {
            $escapedValue = $this->db->real_escape_string($value);
            $fields[] = "$key = '$escapedValue'";
        }

        $query = "UPDATE Sushi_Item SET " . implode(", ", $fields) . " WHERE itemID = '$itemID'";

        if ($this->db->query($query)) {
            return "Sushi item updated successfully!";
        } else {
            throw new Exception("Error: " . $this->db->error);
        }
    }

    public function findById($id) {
        $sql = "SELECT * FROM sushi_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            die("MySQLi Error: " . $this->db->error);
        }
    
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $sushiItem = $result->fetch_assoc();
    
        $stmt->close(); // Close the statement after execution
        return $sushiItem;
    }
    
    public function update($id, $data) {
        $sql = "UPDATE sushi_items SET name = ?, description = ?, price = ?, availability = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            die("MySQLi Error: " . $this->db->error);
        }
    
        $stmt->bind_param("ssdii", $data['name'], $data['description'], $data['price'], $data['availability'], $id);
        $success = $stmt->execute();
    
        $stmt->close(); // Close the statement after execution
        return $success;
    }
    
    public function delete($id) {
        $sql = "DELETE FROM sushi_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            die("MySQLi Error: " . $this->db->error);
        }
    
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
    
        $stmt->close(); // Close the statement after execution
        return $success;
    }
    
}
?>
