<?php
require_once '../controllers/SushiController.php';

$sushiItemController = new SushiItemController();
$sushiItems = $sushiItemController->getAllSushiItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sushi Items</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Sushi Items</h1>
        <a href="add_sushi_item.php" class="add-button">Add New Sushi Item</a>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sushiItems as $item): ?>
                    <tr>
                        <td><?= $item['ItemID'] ?></td>
                        <td><?= $item['ItemName'] ?></td>
                        <td><?= $item['Description'] ?></td>
                        <td><?= $item['Price'] ?></td>
                        <td><?= $item['Category'] ?></td>
                        <td><?= $item['AvailabilityStatus'] ? 'Available' : 'Unavailable' ?></td>
                        <td>
                            <a href="edit _sushi_item.php $item['ItemID'] ?>">Edit</a> |
                            <a href="delete_sushi_item.php?id=<?= $item['ItemID'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>