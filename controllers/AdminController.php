<?php
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/Admin.php';
require_once '../models/SushiItem.php';
require_once '../models/Order.php';

class AdminController {

    // Create a new Admin
    public function createAdmin($data) {
        try {
            $adminData = [
                'name' => sanitizeInput($data['name']),
                'email' => sanitizeInput($data['email']),
                'username' => sanitizeInput($data['username']),
                'password' => password_hash(sanitizeInput($data['password']), PASSWORD_BCRYPT),
                'role' => sanitizeInput($data['role']),
                'contactNumber' => sanitizeInput($data['contactNumber'])
            ];

            $admin = new Admin();
            $result = $admin->create($adminData);

            if (!$result) {
                throw new Exception("Failed to create admin.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in createAdmin: " . $e->getMessage());
            return false;
        }
    }

    // Admin login
    public function login($username, $password) {
        try {
            $admin = new Admin();
            $result = $admin->login($username, $password);

            if (!$result) {
                throw new Exception("Invalid login credentials.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in login: " . $e->getMessage());
            return false;
        }
    }

    // Get admin profile by ID
    public function getProfile($adminID) {
        try {
            $admin = new Admin();
            return $admin->findById($adminID);
        } catch (Exception $e) {
            error_log("Error in getProfile: " . $e->getMessage());
            return false;
        }
    }

    // Update admin profile
    public function updateProfile($adminID, $data) {
        try {
            $sanitizedData = array_map('sanitizeInput', $data);
            $admin = new Admin();
            $result = $admin->update($adminID, $sanitizedData);

            if (!$result) {
                throw new Exception("Failed to update profile.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in updateProfile: " . $e->getMessage());
            return false;
        }
    }

    // Add a new Sushi Item
    public function addSushiItem($data) {
        try {
            $sushiData = [
                'itemName' => sanitizeInput($data['itemName']),
                'description' => sanitizeInput($data['description']),
                'price' => floatval(sanitizeInput($data['price'])),
                'availabilityStatus' => sanitizeInput($data['availabilityStatus']),
                'category' => sanitizeInput($data['category']),
                'ingredients' => sanitizeInput($data['ingredients'])
            ];

            $sushiItem = new SushiItem();
            $result = $sushiItem->create($sushiData);

            if (!$result) {
                throw new Exception("Failed to add sushi item.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in addSushiItem: " . $e->getMessage());
            return false;
        }
    }

    // Update an existing Sushi Item
    public function updateSushiItem($itemID, $data) {
        try {
            $sanitizedData = array_map('sanitizeInput', $data);
            $sushiItem = new SushiItem();
            $result = $sushiItem->update($itemID, $sanitizedData);

            if (!$result) {
                throw new Exception("Failed to update sushi item.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in updateSushiItem: " . $e->getMessage());
            return false;
        }
    }

    // Delete a Sushi Item by ID
    public function deleteSushiItem($itemID) {
        try {
            $sushiItem = new SushiItem();
            $result = $sushiItem->delete($itemID);

            if (!$result) {
                throw new Exception("Failed to delete sushi item.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in deleteSushiItem: " . $e->getMessage());
            return false;
        }
    }

    // Retrieve all orders
    public function getOrders() {
        try {
            $order = new Order();
            return $order->getAll();
        } catch (Exception $e) {
            error_log("Error in getOrders: " . $e->getMessage());
            return false;
        }
    }

    // Update order status
    public function updateOrderStatus($orderID, $status) {
        try {
            $order = new Order();
            $result = $order->updateStatus($orderID, sanitizeInput($status));

            if (!$result) {
                throw new Exception("Failed to update order status.");
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error in updateOrderStatus: " . $e->getMessage());
            return false;
        }
    }
}

class CustomerController {
    // Get all customers
    public function getAllCustomers() {
        $customer = new Customer();
        return $customer->getAll();
    }
}
?>
