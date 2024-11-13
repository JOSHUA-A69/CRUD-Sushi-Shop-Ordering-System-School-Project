<?php
require_once '../lib/BaseController.php';
require_once '../models/Order.php';
require_once '../lib/logger.php';

class OrderController extends BaseController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
        Logger::info("OrderController instantiated.");
    }

    // Place Order Method (unchanged)
    public function placeOrder($data) {
        try {
            Logger::info("Attempting to place order for customerID: " . $data['customerID']);
            $this->validateRequest($data, ['customerID', 'items']);
            $this->validatePermissions($data['customerID'], 'customer');
            
            $orderId = $this->orderModel->create($data);
            
            Logger::info("Order placed successfully with orderId: " . $orderId);
            return $this->respondSuccess(
                ['orderId' => $orderId],
                'Order placed successfully'
            );
        } catch (ValidationException $e) {
            Logger::error("Validation failed for order: " . $e->getMessage());
            return $this->respondError($e->getMessage(), 400);
        } catch (Exception $e) {
            Logger::error("Order placement error: " . $e->getMessage());
            return $this->respondError('Order placement failed', 500);
        }
    }

    // Get Orders Method (final version to keep)
    public function getOrders() {
        try {
            Logger::info("Fetching all orders.");
            
            $orders = $this->orderModel->getAll();  // Fetch orders from the database
            
            // Fetch items for each order
            foreach ($orders as &$order) {
                $orderItems = $this->orderModel->getItemsForOrder($order['orderID']);  // Retrieve order items by orderID
                $order['items'] = $orderItems;
                $order['total_price'] = array_sum(array_map(function($item) {
                    return $item['item_price'] * $item['item_quantity'];
                }, $orderItems));  // Calculate total price based on items
            }

            return $orders;
        } catch (Exception $e) {
            Logger::error("Error retrieving orders: " . $e->getMessage());
            return [];
        }
    }
}
