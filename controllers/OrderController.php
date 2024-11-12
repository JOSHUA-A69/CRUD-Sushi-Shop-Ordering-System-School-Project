<?php
// OrderController.php
require_once '../lib/BaseController.php';
require_once '../models/Order.php';
class OrderController extends BaseController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    public function placeOrder($data) {
        try {
            $this->validateRequest($data, ['customerID', 'items']);
            $this->validatePermissions($data['customerID'], 'customer');
            
            $orderId = $this->orderModel->create($data);
            return $this->respondSuccess(
                ['orderId' => $orderId],
                'Order placed successfully'
            );
        } catch (ValidationException $e) {
            return $this->respondError($e->getMessage(), 400);
        } catch (Exception $e) {
            Logger::error("Order placement error: " . $e->getMessage());
            return $this->respondError('Order placement failed', 500);
        }
    }
}

?>