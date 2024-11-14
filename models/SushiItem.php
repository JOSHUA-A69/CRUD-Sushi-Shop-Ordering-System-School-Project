<?php
// SushiItem.php
require_once '../lib/BaseModel.php';

class SushiItem extends BaseModel {
    protected $table = 'sushi_item';
    protected $allowedFields = ['itemName', 'description', 'price', 
                                'availabilityStatus', 'category', 'ingredients'];   

         //Create new sushi items                       
         public function create($data) {
         try {
        // Prepare the statement
         $result = $this->executeStatement(
        "INSERT INTO {$this->table} (itemName, description, price, availabilityStatus, category, ingredients) 
         VALUES (?, ?, ?, ?, ?, ?)",
         [
        $data['itemName'],
        $data['description'],
        $data['price'],
        $data['availabilityStatus'],
        $data['category'],
        $data['ingredients']
      ],
      "ssdsis" // Ensure correct types for each parameter
      );
                                
     // Return the last inserted ID if successful
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
        $params = [$data['itemName'], $data['description'], $data['price'], $data['availabilityStatus'], $data['category'], $id];
        return $this->executeStatement($query, $params, "ssdsii");
    }

    public function delete($id) {
        // Use 'ItemID' as the correct primary key if that's your database's setup
        $query = "DELETE FROM {$this->table} WHERE ItemID = ?";
        $result = $this->executeStatement($query, [$id], "i");
    
        // Return true if delete was successful, otherwise false
        return $result && $this->db->affected_rows > 0;
    }
}
?>
