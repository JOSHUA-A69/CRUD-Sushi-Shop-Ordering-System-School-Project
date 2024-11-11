<?php
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/Admin.php';
require_once '../models/SushiItem.php';
require_once '../models/Order.php';

class AdminController {
    
    // Create a new Admin
    public function createAdmin($data) {
        $adminData = [
            'name' => sanitizeInput($data['name']),
            'email' => sanitizeInput($data['email']),
            'username' => sanitizeInput($data['username']),
            'password' => password_hash(sanitizeInput($data['password']), PASSWORD_BCRYPT),
            'role' => sanitizeInput($data['role']),
            'contactNumber' => sanitizeInput($data['contactNumber'])
        ];
        
        $admin = new Admin();
        return $admin->create($adminData);
    }

    // Admin login
    public function login($username, $password) {
        $admin = new Admin();
        return $admin->login($username, $password);
    }

    // Get admin profile by ID
    public function getProfile($adminID) {
        $admin = new Admin();
        return $admin->findById($adminID);
    }

    // Update admin profile
    public function updateProfile($adminID, $data) {
        $sanitizedData = array_map('sanitizeInput', $data);
        $admin = new Admin();
        return $admin->update($adminID, $sanitizedData);
    }

    // Add a new Sushi Item
    public function addSushiItem($data) {
        $sushiData = [
            'itemName' => sanitizeInput($data['itemName']),
            'description' => sanitizeInput($data['description']),
            'price' => sanitizeInput($data['price']),
            'availabilityStatus' => sanitizeInput($data['availabilityStatus']),
            'category' => sanitizeInput($data['category']),
            'ingredients' => sanitizeInput($data['ingredients'])
        ];
        
        $sushiItem = new SushiItem();
        return $sushiItem->create($sushiData);
    }

    // Update an existing Sushi Item
    public function updateSushiItem($itemID, $data) {
        $sanitizedData = array_map('sanitizeInput', $data);
        $sushiItem = new SushiItem();
        return $sushiItem->update($itemID, $sanitizedData);
    }

    // Delete a Sushi Item by ID
    public function deleteSushiItem($itemID) {
        $sushiItem = new SushiItem();
        return $sushiItem->delete($itemID);
    }

    // Retrieve all orders
    public function getOrders() {
        $order = new Order();
        return $order->getAll();
    }

    // Update order status
    public function updateOrderStatus($orderID, $status) {
        $order = new Order();
        return $order->updateStatus($orderID, sanitizeInput($status));
    }
}
?>
