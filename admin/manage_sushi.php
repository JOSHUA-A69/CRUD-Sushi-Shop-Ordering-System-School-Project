<?php
require_once '../controllers/SushiController.php';

$sushiItemController = new SushiItemController();
$sushiItems = $sushiItemController->getAllSushiItems();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sushi Items</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_sushi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Manage Sushi Items</h1>
        <div class="text-end mb-3">
            <a href="add_sushi_item.php" class="btn btn-success">Add New Sushi Item</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
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
                            <td><?= htmlspecialchars($item['ItemID']) ?></td>
                            <td><?= htmlspecialchars($item['ItemName']) ?></td>
                            <td><?= htmlspecialchars($item['Description']) ?></td>
                            <td>$<?= number_format($item['Price'], 2) ?></td>
                            <td><?= htmlspecialchars($item['Category']) ?></td>
                            <td>
                                <span class="badge <?= $item['AvailabilityStatus'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $item['AvailabilityStatus'] ? 'Available' : 'Unavailable' ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit _sushi_item.php?id=<?= $item['ItemID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square">Edit</i>
                                </a>
                                <a href="delete_sushi_item.php?id=<?= $item['ItemID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">
                                    <i class="bi bi-trash">Delete</i> 
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS (Optional for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
