<?php
require_once '../models/Order.php';

class OrderController {
    // Place a new order
    public function placeOrder($data) {
        if (!$this->validateOrderData($data)) {
            return "Invalid order data."; 
        }
        
        try {
            $order = new Order();
            return $order->create($data); // Use the create method from the Order model
        } catch (Exception $e) {
            // Log or handle the exception as needed
            return "Error placing order: " . $e->getMessage();
        }
    }

    // Get details of a specific order
    public function getOrderDetails($orderID) {
        try {
            $order = new Order();
            return $order->findById($orderID); // Use the findById method from the Order model
        } catch (Exception $e) {
            return "Error retrieving order details: " . $e->getMessage();
        }
    }

    // Get orders for a specific customer
    public function getCustomerOrders($customerID) {
        try {
            $order = new Order();
            return $order->findByCustomerId($customerID); // Use the findByCustomerId method from the Order model
        } catch (Exception $e) {
            return "Error retrieving customer orders: " . $e->getMessage();
        }
    }

    // Validate the order data before placing the order
    private function validateOrderData($data) {
        return isset($data['customerID']) && !empty($data['customerID']) &&
               isset($data['items']) && is_array($data['items']) && count($data['items']) > 0;
    }

    public function getOrderById($orderID) {
        $order = new Order();
        return $order->findById($orderID);
    }

    // Method to update order status
    public function updateOrderStatus($orderID, $status) {
        $order = new Order();
        return $order->updateStatus($orderID, $status);
    }
}
?>

