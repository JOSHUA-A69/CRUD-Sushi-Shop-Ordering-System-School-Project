<?php

require_once '../config/database.php';

abstract class BaseModel {
    protected $db;
    protected $table;
    protected $allowedFields = [];
    protected static $connections = 0;
    
    public function __construct() {
        // Implement connection pooling to prevent too many connections
        if (self::$connections >= 100) { // Adjust max connections as needed
            throw new Exception("Too many database connections");
        }
        
        try {
            $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$connections++;
            
            if ($this->db->connect_error) {
                throw new Exception("Connection failed: " . $this->db->connect_error);
            }
            
            // Set proper charset and collation
            $this->db->set_charset('utf8mb4');
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }
    
    protected function validateData(array $data): array {
        $validatedData = [];
        foreach ($this->allowedFields as $field) {
            if (isset($data[$field])) {
                $validatedData[$field] = $this->sanitizeInput($data[$field]);
            }
        }
        return $validatedData;
    }
    
    protected function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    protected function executeStatement($query, $params = [], $types = '') {
        try {
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->db->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function beginTransaction() {
        $this->db->begin_transaction();
    }
    
    public function commit() {
        $this->db->commit();
    }
    
    public function rollback() {
        $this->db->rollback();
    }
    
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
            self::$connections--;
        }
    }
}

?>

