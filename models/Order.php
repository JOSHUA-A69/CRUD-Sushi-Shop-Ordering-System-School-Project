<?php
// Order.php
require_once '../lib/BaseModel.php';
class Order extends BaseModel {
    protected $table = 'orders';
    protected $allowedFields = ['customerID', 'items', 'status', 'orderDate'];
    
    public function create($data) {
        try {
            $this->beginTransaction();
            
            $validatedData = $this->validateData($data);
            $validatedData['items'] = json_encode($validatedData['items']);
            $validatedData['orderDate'] = date('Y-m-d H:i:s');
            $validatedData['status'] = 'pending';
            
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (customerID, items, status, orderDate) 
                 VALUES (?, ?, ?, ?)",
                [$validatedData['customerID'], $validatedData['items'], 
                 $validatedData['status'], $validatedData['orderDate']],
                "ssss"
            );
            
            $orderId = $this->db->insert_id;
            $this->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->rollback();
            Logger::error("Order creation failed: " . $e->getMessage());
            throw $e;
        }
    }
}

?>