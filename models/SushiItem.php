<?php
require_once '../config/database.php';

class SushiItem {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Create a new sushi item
    public function create($data) {
        $sql = "INSERT INTO sushi_items (itemName, description, price, availabilityStatus, category, ingredients, imagePath)
                VALUES (:itemName, :description, :price, :availabilityStatus, :category, :ingredients, :imagePath)";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind values
        $stmt->bindParam(':itemName', $data['itemName']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':availabilityStatus', $data['availabilityStatus']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':ingredients', $data['ingredients']);
        $stmt->bindParam(':imagePath', $data['imagePath']); // Assumes `imagePath` key in $data for image path
        
        return $stmt->execute();
    }

    // Retrieve all sushi items
    public function getAll() {
        $sql = "SELECT * FROM sushi_items";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update the price of a sushi item by ID
    public function updatePrice($itemID, $price) {
        $sql = "UPDATE sushi_items SET price = :price WHERE itemID = :itemID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':itemID', $itemID);
        return $stmt->execute();
    }

    // Update the availability status of a sushi item by ID
    public function updateAvailability($itemID, $availabilityStatus) {
        $sql = "UPDATE sushi_items SET availabilityStatus = :availabilityStatus WHERE itemID = :itemID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':availabilityStatus', $availabilityStatus);
        $stmt->bindParam(':itemID', $itemID);
        return $stmt->execute();
    }

    // Delete a sushi item by ID
    public function delete($itemID) {
        $sql = "DELETE FROM sushi_items WHERE itemID = :itemID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemID', $itemID);
        return $stmt->execute();
    }
}
?>
