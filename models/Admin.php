<?php
// Admin.php
require_once '../lib/BaseModel.php';
class Admin extends BaseModel {
    protected $table = 'administrators';
    protected $allowedFields = ['name', 'email', 'username', 'password', 'role', 'contactNumber'];
    
    public function create($data) {
        try {
            $validatedData = $this->validateData($data);
            $validatedData['password'] = password_hash($validatedData['password'], PASSWORD_BCRYPT);
            
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (name, email, username, password, role, contactNumber) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$validatedData['name'], $validatedData['email'], $validatedData['username'],
                 $validatedData['password'], $validatedData['role'], $validatedData['contactNumber']],
                "ssssss"
            );
            
            return $this->db->insert_id;
        } catch (Exception $e) {
            Logger::error("Admin creation failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function login($username, $password) {
        try {
            $result = $this->executeStatement(
                "SELECT * FROM {$this->table} WHERE username = ?",
                [$username],
                "s"
            );
            
            $admin = $result->fetch_assoc();
            if ($admin && password_verify($password, $admin['password'])) {
                unset($admin['password']); // Don't return password
                return $admin;
            }
            return false;
        } catch (Exception $e) {
            Logger::error("Admin login failed: " . $e->getMessage());
            throw $e;
        }
    }
}

?>