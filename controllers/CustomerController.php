<?php
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/Customer.php';
require_once '../models/SushiItem.php';

class CustomerController {
    
    // Method to create an admin (or customer)
    public function createAdmin($data) {
        $customer = new Customer();
        $adminData = [
            'firstName' => sanitizeInput($data['firstName']),
            'middleInitial' => sanitizeInput($data['middleInitial']),
            'lastName' => sanitizeInput($data['lastName']),
            'email' => sanitizeInput($data['email']),
            'phoneNumber' => sanitizeInput($data['phoneNumber']),
            'city' => sanitizeInput($data['city']),
            'street' => sanitizeInput($data['street']),
            'houseNumber' => sanitizeInput($data['houseNumber']),
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => 'admin'  // differentiating between customer/admin
        ];
        
        return $customer->create($adminData);
    }

    // Method for customer signup
    public function signup($data) {
        $customer = new Customer();
        return $customer->create($data);
    }

    // Method for customer login
    public function login($email, $password) {
        $customer = new Customer();
        return $customer->login($email, $password);
    }

    // Method to get customer profile
    public function getProfile($customerID) {
        $customer = new Customer();
        return $customer->findById($customerID);
    }

    // Method to update customer profile
    public function updateProfile($customerID, $data) {
        $customer = new Customer();
        return $customer->update($customerID, $data);
    }

    // Method to get the sushi menu
    public function getSushiMenu() {
        $sushiItem = new SushiItem();
        return $sushiItem->getAll();
     }

     public function deleteCustomer($customerID) {
        $customer = new Customer();
        return $customer->delete($customerID);
    }
      
}
