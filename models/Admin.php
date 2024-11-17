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
            // Query to fetch admin details, including username and password
            $result = $this->executeStatement(
                "SELECT AdminID, Name, Email, ContactNumber, Username, Password, Role 
                 FROM {$this->table} 
                 WHERE AdminID = ?",
                [$adminId],
                "i"
            );
    
            // Fetch and return the admin's profile data
            $admin = $result->fetch_assoc();
            return $admin ?: false; // Return admin data if found, or false if not found
        } catch (Exception $e) {
            Logger::error("Get profile failed: " . $e->getMessage());
            throw $e; // Re-throw the exception to handle it in the controller
        }
    }
    
    public function updateProfileById($adminId, $updatedData) {
        try {
            // Start the base SQL query
            $sql = "UPDATE {$this->table} 
                    SET name = ?, email = ?, contactNumber = ?, username = ?, role = ?";
    
            // Initialize parameters and types for binding
            $params = [
                $updatedData['name'],
                $updatedData['email'],
                $updatedData['contactNumber'],
                $updatedData['username'],
                $updatedData['role']
            ];
            $types = "sssss"; // Corresponding parameter types for binding
    
            // Check if a password is included and append it to the query
            if (!empty($updatedData['password'])) {
                $sql .= ", password = ?";
                $params[] = $updatedData['password']; // Add the hashed password
                $types .= "s"; // Add the parameter type for the password
            }
    
            // Append the WHERE clause
            $sql .= " WHERE AdminID = ?";
            $params[] = $adminId; // Add the admin ID to the parameters
            $types .= "i"; // Add the parameter type for the admin ID
    
            // Execute the statement
            return $this->executeStatement($sql, $params, $types);
        } catch (Exception $e) {
            Logger::error("Update profile failed: " . $e->getMessage());
            throw $e; // Re-throw the exception to be handled by the controller
        }
    }
    
}


?>