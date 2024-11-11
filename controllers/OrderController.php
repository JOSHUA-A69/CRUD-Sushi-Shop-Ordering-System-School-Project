<?php
require_once '../models/Order.php';

class OrderController {
    public function placeOrder($data) {
        $order = new Order();
        return $order->create($data);
    }

    public function getOrderDetails($orderID) {
        $order = new Order();
        return $order->findById($orderID);
    }

    public function getCustomerOrders($customerID) {
        $order = new Order();
        return $order->findByCustomerId($customerID);
    }
}
