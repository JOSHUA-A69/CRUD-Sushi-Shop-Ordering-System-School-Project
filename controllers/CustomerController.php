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
        $query = "SELECT CustomerID, FirstName, LastName, Email, PhoneNumber FROM Customers"; 
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

    public function getProfile($customerID) {
        // Query to get the customer profile by ID
        $query = "SELECT CustomerID, firstName, middleInitial, lastName, email, phoneNumber, street, citytown, houseNumber FROM customers WHERE CustomerID = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerID); // Ensure this matches the function parameter
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if customer was found
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No customer found with the given ID
        }
    }

    public function updateProfile($customerID, $firstName, $middleInitial, $lastName, $email, $phoneNumber, $street, $citytown, $houseNumber) {
        // SQL query to update customer information
        $query = "UPDATE customers 
                  SET firstName = ?, middleInitial = ?, lastName = ?, email = ?, phoneNumber = ?, street = ?, citytown = ?, houseNumber = ?
                  WHERE CustomerID = ?";
    
        // Prepare the statement
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("ssssssssi", $firstName, $middleInitial, $lastName, $email, $phoneNumber, $street, $citytown, $houseNumber, $customerID);
    
        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }
    
    public function deleteCustomer($customerID) {
        // SQL query to delete the customer by ID
        $query = "DELETE FROM customers WHERE CustomerID = ?";
        
        // Prepare the statement
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerID);
    
        // Execute the query and check if the deletion was successful
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0; // Returns true if a row was deleted
        } else {
            return false; // Returns false if the deletion failed
        }
    }
    
    public function __destruct() {
        // Close the database connection
        $this->db->close();
}
 }
?>
