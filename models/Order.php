<?php
// Order.php
require_once '../lib/BaseModel.php';
class Order extends BaseModel {
    protected $table = 'orders';
    protected $allowedFields = ['customerID', 'status', 'orderDate'];

    public function create($data) {
        try {
            $this->beginTransaction();
            
            // Validate data
            $validatedData = $this->validateData($data);
            $validatedData['orderDate'] = date('Y-m-d H:i:s');
            $validatedData['status'] = 'pending';

            // Insert order into the orders table
            $result = $this->executeStatement(
                "INSERT INTO {$this->table} (customerID, status, orderDate) 
                 VALUES (?, ?, ?)",
                [$validatedData['customerID'], $validatedData['status'], $validatedData['orderDate']],
                "sss"
            );
            
            // Get the ID of the newly inserted order
            $orderId = $this->db->insert_id;

            // Insert items into the order_items table
            if (!empty($validatedData['items'])) {
                foreach ($validatedData['items'] as $item) {
                    $this->executeStatement(
                        "INSERT INTO order_items (orderID, item_name, item_quantity, item_price)
                         VALUES (?, ?, ?, ?)",
                        [$orderId, $item['name'], $item['quantity'], $item['price']],
                        "isid"
                    );
                }
            }

            $this->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->rollback();
            Logger::error("Order creation failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAll() {
        try {
            // Fetch order details along with associated items
            $query = "SELECT o.orderID, o.customerID, o.status, o.orderDate, 
                             oi.item_name, oi.item_quantity, oi.item_price
                      FROM {$this->table} o
                      LEFT JOIN order_items oi ON o.orderID = oi.orderID";
    
            $result = $this->db->query($query);
    
            if (!$result) {
                Logger::error("Error executing query in getAll: " . $this->db->error);
                return [];
            }
    
            // Check if any data was returned
            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                Logger::info("No orders found in the database.");
                return [];
            }
        } catch (Exception $e) {
            Logger::error("Exception in getAll: " . $e->getMessage());
            return [];
        }
    }
    

    public function getItemsForOrder($orderID) {
        $query = "SELECT orderID, customerID, status FROM Orders WHERE orderID = ?";
        $result = $this->executeStatement($query, [$orderID], "i");

        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
 ?>