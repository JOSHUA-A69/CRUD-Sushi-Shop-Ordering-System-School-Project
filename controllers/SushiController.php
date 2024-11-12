<?php
require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../models/SushiItem.php';

class SushiItemController {

    // Add a new Sushi item
    public function addItem($data) {
        $sushiData = [
            'itemName' => sanitizeInput($data['itemName']),
            'description' => sanitizeInput($data['description']),
            'price' => sanitizeInput($data['price']),
            'availabilityStatus' => sanitizeInput($data['availabilityStatus']),
            'category' => sanitizeInput($data['category']),
            'ingredients' => sanitizeInput($data['ingredients'])
        ];

        $sushiItem = new SushiItem();
        return $sushiItem->create($sushiData);
    }

    // List all Sushi items
    public function listItems() {
        $sushiItem = new SushiItem();
        return $sushiItem->getAll();
    }

    // Delete a Sushi item by ID
    public function deleteItem($itemID) {
        $sushiItem = new SushiItem();
        return $sushiItem->delete($itemID);
    }

    // Update Sushi item price
    public function updateItemPrice($itemID, $price) {
        $sushiItem = new SushiItem();
        return $sushiItem->update($itemID, ['price' => sanitizeInput($price)]);
    }

    // Update Sushi item availability status
    public function updateItemAvailability($itemID, $availabilityStatus) {
        $sushiItem = new SushiItem();
        return $sushiItem->update($itemID, ['availabilityStatus' => sanitizeInput($availabilityStatus)]);
    }

    public function getSushiItemById($id) {
        $sushiItem = new SushiItem();
        return $sushiItem->findById($id);
    }

    public function updateSushiItem($id, $data) {
        $sushiItem = new SushiItem();
        return $sushiItem->update($id, $data);
    }

    public function deleteSushiItem($id) {
        $sushiItem = new SushiItem();
        return $sushiItem->delete($id);
    }
}
?>
