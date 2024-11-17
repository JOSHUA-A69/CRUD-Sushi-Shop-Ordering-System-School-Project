<?php
require_once '../config/database.php';
require_once '../controllers/SushiController.php';

$sushiItemController = new SushiItemController();
$sushiItems = $sushiItemController->getAllSushiItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="styles/menu.css">
</head>
<body>
    <h1>Our Menu</h1>
    <div class="menu">
        <?php if (!empty($sushiItems)): ?>
            <?php foreach ($sushiItems as $row): ?>
                <div class="menu-item">
                    <h2>
                        <?php echo !empty($row['ItemName']) ? htmlspecialchars($row['ItemName']) : 'No Name Available'; ?>
                    </h2>
                    <p>
                        <?php echo !empty($row['Description']) ? htmlspecialchars($row['Description']) : 'No Description Available'; ?>
                    </p>
                    <p>
                        Price: $
                        <?php echo isset($row['Price']) ? number_format((float)$row['Price'], 2) : '0.00'; ?>
                    </p>
                    <a href="order.php?id=<?php echo isset($row['ItemID']) ? htmlspecialchars($row['ItemID']) : ''; ?>" 
                       class="order-btn">
                        Order Now
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No items available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
