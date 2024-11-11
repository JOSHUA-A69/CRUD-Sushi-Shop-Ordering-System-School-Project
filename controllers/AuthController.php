<?php 
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/Admin.php';
require_once '../models/Customer.php';

class AuthContorller{
    public function loginAdmin($data){
        $username = sanitizeInput($data['username']);
        $password = sanitizeInput($data['password']);

        $admin = new Admin();
        return $admin->login($username, $password);
    }

    public function loginCustomer($data){
        $email = sanitizeInput($data['email']);
        $password = sanitizeInput($data['password']);

        $customer = new Customer();
        return $customer->login($email, $password);

}

public function logout() {
    session_start();
    session_unset();
    session_destroy();
}

}