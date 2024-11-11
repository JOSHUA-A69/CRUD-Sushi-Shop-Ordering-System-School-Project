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
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sushiItems as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['availability'] ? 'Available' : 'Unavailable' ?></td>
                        <td>
                            <a href="edit_sushi_item.php?id=<?= $item['id'] ?>">Edit</a> |
                            <a href="delete_sushi_item.php?id=<?= $item['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>