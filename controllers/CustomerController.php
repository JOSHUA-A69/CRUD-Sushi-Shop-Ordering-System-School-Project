<?php
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/Customer.php';
require_once '../models/SushiItem.php';

class CustomerController {
    // Method to create an admin (or customer, depending on the functionality)
    public function createAdmin($data) {
        $firstName = sanitizeInput($data['firstName']);
        $middleInitial = sanitizeInput($data['middleInitial']);
        $lastName = sanitizeInput($data['lastName']);
        $email = sanitizeInput($data['email']);
        $phoneNumber = sanitizeInput($data['phoneNumber']);
        $city = sanitizeInput($data['city']);
        $street = sanitizeInput($data['street']);
        $houseNumber = sanitizeInput($data['houseNumber']);
        
        // Encrypt the password
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $customer = new Customer();
        
        // Assuming the Customer model has a create method for admin/customer creation
        return $customer->create([
            'firstName' => $firstName,
            'middleInitial' => $middleInitial,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'city' => $city,
            'street' => $street,
            'houseNumber' => $houseNumber,
            'password' => $password,
            'role' => 'admin'  // assuming 'role' can differentiate between customer/admin
        ]);
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
}
