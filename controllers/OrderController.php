<?php
// OrderController.php
require_once '../lib/BaseController.php';
require_once '../models/Order.php';
require_once '../lib/logger.php';
class OrderController extends BaseController {
    private $orderModel;
    
    // Logger added to constructor
    public function __construct() {
        $this->orderModel = new Order();
        
        // Log the creation of the OrderController instance
        Logger::info("OrderController instantiated.");
    }
    
    public function placeOrder($data) {
        try {
            // Log the order placement attempt
            Logger::info("Attempting to place order for customerID: " . $data['customerID']);
            
            $this->validateRequest($data, ['customerID', 'items']);
            $this->validatePermissions($data['customerID'], 'customer');
            
            $orderId = $this->orderModel->create($data);
            
            // Log successful order placement
            Logger::info("Order placed successfully with orderId: " . $orderId);
            
            return $this->respondSuccess(
                ['orderId' => $orderId],
                'Order placed successfully'
            );
        } catch (ValidationException $e) {
            // Log validation error
            Logger::error("Validation failed for order: " . $e->getMessage());
            return $this->respondError($e->getMessage(), 400);
        } catch (Exception $e) {
            // Log general error during order placement
            Logger::error("Order placement error: " . $e->getMessage());
            return $this->respondError('Order placement failed', 500);
        }
    }

    public function getOrders() {
        try {
            // Log the attempt to fetch orders
            Logger::info("Fetching all orders.");
            
            // Fetch all orders from the model
            $orders = $this->orderModel->getAll();
    
            if (!$orders) {
                // Log if no orders are found
                Logger::info("No orders found.");
                return []; // Return an empty array if no orders found
            }
    
            // Log successful retrieval of orders
            Logger::info("Successfully retrieved orders.");
            return $orders; // Return all orders
        } catch (Exception $e) {
            // Log error if fetching orders fails
            Logger::error("Error retrieving orders: " . $e->getMessage());
            return [];
        }
    }
}

?>