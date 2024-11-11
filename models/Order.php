<?php
require_once '../config/database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Method to create a new order
    public function create($customerID, $items, $totalAmount) {
        try {
            $this->db->beginTransaction();

            // Insert into orders table
            $sql = "INSERT INTO orders (customerID, totalAmount, status, orderDate) 
                    VALUES (:customerID, :totalAmount, 'Pending', NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':customerID', $customerID);
            $stmt->bindParam(':totalAmount', $totalAmount);
            $stmt->execute();
            $orderID = $this->db->lastInsertId();

            // Insert each item into order_items table
            $sqlItem = "INSERT INTO order_items (orderID, itemID, quantity, price) 
                        VALUES (:orderID, :itemID, :quantity, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            foreach ($items as $item) {
                $stmtItem->bindParam(':orderID', $orderID);
                $stmtItem->bindParam(':itemID', $item['itemID']);
                $stmtItem->bindParam(':quantity', $item['quantity']);
                $stmtItem->bindParam(':price', $item['price']);
                $stmtItem->execute();
            }

            $this->db->commit();
            return $orderID;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Method to find an order by ID
    public function findById($orderID) {
        $sql = "SELECT * FROM orders WHERE orderID = :orderID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderID', $orderID);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            $order['items'] = $this->getOrderItems($orderID);
        }
        return $order;
    }

    // Method to get all orders
    public function getAll() {
        $sql = "SELECT * FROM orders";
        $stmt = $this->db->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['orderID']);
        }
        return $orders;
    }

    // Method to get orders by customer ID
    public function findByCustomerId($customerID) {
        $sql = "SELECT * FROM orders WHERE customerID = :customerID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':customerID', $customerID);
        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['orderID']);
        }
        return $orders;
    }

    // Helper method to get items in an order
    private function getOrderItems($orderID) {
        $sql = "SELECT oi.*, si.itemName FROM order_items oi 
                JOIN sushi_items si ON oi.itemID = si.itemID 
                WHERE oi.orderID = :orderID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderID', $orderID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to update order status
    public function updateStatus($orderID, $status) {
        $sql = "UPDATE orders SET status = :status WHERE orderID = :orderID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderID', $orderID);

        return $stmt->execute();
    }
}
