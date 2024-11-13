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
 
    public function getProfileById($adminId) {
        try {
            $result = $this->executeStatement(
                "SELECT AdminID, Name, Email, ContactNumber, role FROM {$this->table} WHERE AdminId = ?",
                [$adminId],
                "i"
            );
            
            $admin = $result->fetch_assoc();
            return $admin ?: false;  // Return admin data if found, or false if not found
        } catch (Exception $e) {
            Logger::error("Get profile failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateProfileById($adminId, $updatedData) {
        try {
            // Prepare the SQL statement to update the admin profile
            $sql = "UPDATE {$this->table} SET name = ?, email = ?, contactNumber = ? WHERE  AdminID = ?";
            $params = [
                $updatedData['name'],
                $updatedData['email'],
                $updatedData['contactNumber'],
                $adminId
            ];

            // Execute the statement
            return $this->executeStatement($sql, $params, "sssi");
        } catch (Exception $e) {
            Logger::error("Update profile failed: " . $e->getMessage());
            throw $e;
        }
    }  
}


?>