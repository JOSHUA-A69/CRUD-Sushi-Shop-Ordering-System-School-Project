<?php
// CustomerController.php
require_once '../config/database.php';
require_once '../lib/BaseController.php';
require_once '../models/Customer.php';

class CustomerController extends BaseController {
    private $db;
    private $customerModel;

    public function __construct() {
        // Establish database connection
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check for connection errors
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Initialize the Customer model
        $this->customerModel = new Customer();
    }

    public function signup($data) {
        try {
            $this->validateRequest($data, [
                'firstName', 'lastName', 'email', 'password', 'phoneNumber'
            ]);

            $customerId = $this->customerModel->create($data);
            return $this->respondSuccess(
                ['id' => $customerId], 
                'Customer registered successfully'
            );
        } catch (ValidationException $e) {
            return $this->respondError($e->getMessage(), 400);
        } catch (Exception $e) {
            Logger::error("Customer signup error: " . $e->getMessage());
            return $this->respondError('Registration failed', 500);
        }
    }
    
    public function getAllCustomers() {
        $query = "SELECT id, name, email, phone FROM Customers"; 
        $result = $this->db->query($query);
    
        if (!$result) {
            die("Error fetching customers: " . $this->db->error);
        }
    
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
    
        return $customers;
    }
    
    public function __destruct() {
        // Close the database connection
        $this->db->close();
}
 }
?>
