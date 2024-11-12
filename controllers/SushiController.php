<?php
// SushiItemController.php
require_once '../lib/BaseController.php';
require_once '../models/SushiItem.php';
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
}

?>