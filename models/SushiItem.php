<?php
// SushiItem.php
require_once '../lib/BaseModel.php';

class SushiItem extends BaseModel {
    protected $table = 'sushi_item';
    protected $allowedFields = ['itemName', 'description', 'price', 
                                'availabilityStatus', 'category', 'ingredients'];

    // Create a new sushi item
    public function create($data) {
        try {
            // Validate the data
            $validatedData = $this->validateData($data);

            // Ensure field names in the query are consistent with the data
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (itemName, description, price, availabilityStatus, category, ingredients) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                array_values($validatedData),
                "ssdsis" // Ensure correct types for each parameter
            );

            // Return the last inserted ID
            return $this->db->insert_id;
        } catch (Exception $e) {
            Logger::error("Sushi item creation failed: " . $e->getMessage());
            throw $e;
        }
    }

    // Get all sushi items
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->db->query($query);
        
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Fetch a single sushi item by ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->executeStatement($query, [$id], "i");

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    // Update sushi item
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET itemName = ?, description = ?, price = ?, availabilityStatus = ? WHERE id = ?";
        $params = [$data['itemName'], $data['description'], $data['price'], $data['availabilityStatus'], $id];
        return $this->executeStatement($query, $params, "ssdsii");
    }

    public function delete($id) {
        // Update 'id' to the actual primary key field of your 'sushi_item' table
        $query = "DELETE FROM {$this->table} WHERE ItemID = ?";
        return $this->executeStatement($query, [$id], "i");
    }
}
?>
