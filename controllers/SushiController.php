<?php
// SushiItemController.php
require_once '../lib/BaseController.php';
require_once '../models/SushiItem.php';
require_once '../lib/logger.php';
class SushiItemController extends BaseController {
    private $sushiItemModel;
    
    public function __construct() {
        $this->sushiItemModel = new SushiItem();
    }
    
    public function addItem($data) {
        try {
            $this->validateRequest($data, [
                'itemName', 'description', 'price', 'category'
            ]);
            $this->validatePermissions($_SESSION['admin_id'], 'admin');
            
            $itemId = $this->sushiItemModel->create($data);
            return $this->respondSuccess(
                ['id' => $itemId],
                'Sushi item added successfully'
            );
        } catch (ValidationException $e) {
            return $this->respondError($e->getMessage(), 400);
        } catch (AuthorizationException $e) {
            return $this->respondError($e->getMessage(), 403);
        } catch (Exception $e) {
            Logger::error("Sushi item creation error: " . $e->getMessage());
            return $this->respondError('Failed to add sushi item', 500);
        }
    }

    public function getAllSushiItems() {
        return $this->sushiItemModel->getAll();
    }

     // Fetch a single sushi item by ID
     public function getSushiItemById($id) {
        return $this->sushiItemModel->getById($id);
    }

    // Update sushi item
    public function updateSushiItem($id, $data) {
        return $this->sushiItemModel->update($id, $data);
    }

    // Delete sushi item
    public function deleteSushiItem($id) {
        try {
            if ($this->sushiItemModel->delete($id)) {
                return true; // Successfully deleted
            } else {
                Logger::error("Failed to delete sushi item with ID $id: Item not found or could not be deleted.");
                return false;
            }
        } catch (Exception $e) {
            Logger::error("Error deleting sushi item with ID $id: " . $e->getMessage());
            return false;
        }
    }

    // Function to add a new sushi item
    public function addSushiItem($data) {
        try {
            // Ensure consistency with form field names
            if (empty($data['ItemName']) || empty($data['Price']) || empty($data['Description']) || empty($data['Category']) || empty($data['AvailabilityStatus']) || empty($data['Ingredients'])) {
                throw new Exception("All fields are required.");
            }
            
            // Pass the data to the model to insert into the database
            $result = $this->sushiItemModel->create($data);
    
            // Check if the insert was successful
            if ($result) {
                header("Location: manage_sushi.php?success=1");
                exit();
            } else {
                throw new Exception("Failed to add sushi item.");
            }
        } catch (Exception $e) {
            // Log and display the error message
            Logger::error("Sushi item creation failed: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    } 

}



?>