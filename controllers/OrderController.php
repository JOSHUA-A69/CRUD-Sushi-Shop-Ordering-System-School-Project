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

    public function getOrders() {
        try {
            Logger::info("Fetching all orders.");
            
            // Fetch basic order details
            $orders = $this->orderModel->getAll();
            
            // Debug output for verification
            echo "<pre>";
            print_r($orders);  // Output raw data fetched
            echo "</pre>";
            
            // Process fetched data to group items per order
            $ordersById = [];
            foreach ($orders as $order) {
                if (!isset($ordersById[$order['orderID']])) {
                    $ordersById[$order['orderID']] = [
                        'orderID' => $order['orderID'],
                        'customerID' => $order['customerID'],
                        'OrderStatus' => $order['status'],
                        'items' => [],
                        'TotalPrice' => 0
                    ];
                }
    
                if (!empty($order['item_name'])) {
                    $ordersById[$order['orderID']]['items'][] = [
                        'ItemName' => $order['item_name'],
                        'quantity' => $order['item_quantity'],
                        'price' => $order['item_price']
                    ];
                    $ordersById[$order['orderID']]['TotalPrice'] += $order['item_quantity'] * $order['item_price'];
                }
            }
    
            return array_values($ordersById);  
        } catch (Exception $e) {
            Logger::error("Error retrieving orders: " . $e->getMessage());
            return [];
        }
    }
    
}
