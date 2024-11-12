<?php
// Customer.php
require_once '../lib/BaseModel.php';
class Customer extends BaseModel {
    protected $table = 'customers';
    protected $allowedFields = ['firstName', 'middleInitial', 'lastName', 'email', 
                              'phoneNumber', 'city', 'street', 'houseNumber', 'password'];
    
    public function create($data) {
        try {
            $this->beginTransaction();
            
            $validatedData = $this->validateData($data);
            $validatedData['password'] = password_hash($validatedData['password'], PASSWORD_BCRYPT);
            
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (firstName, middleInitial, lastName, email, 
                 phoneNumber, city, street, houseNumber, password) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                array_values($validatedData),
                "sssssssss"
            );
            
            $this->commit();
            return $this->db->insert_id;
        } catch (Exception $e) {
            $this->rollback();
            Logger::error("Customer creation failed: " . $e->getMessage());
            throw $e;
        }
    }
}

?>