<?php
// SushiItem.php
require_once '../lib/BaseModel.php';
class SushiItem extends BaseModel {
    protected $table = 'sushi_item';
    protected $allowedFields = ['itemName', 'description', 'price', 
                              'availabilityStatus', 'category', 'ingredients'];
    
    public function create($data) {
        try {
            $validatedData = $this->validateData($data);
            
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (itemName, description, price, 
                 availabilityStatus, category, ingredients) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                array_values($validatedData),
                "ssdsis"
            );
            
            return $this->db->insert_id;
        } catch (Exception $e) {
            Logger::error("Sushi item creation failed: " . $e->getMessage());
            throw $e;
        }
    }
}

?>