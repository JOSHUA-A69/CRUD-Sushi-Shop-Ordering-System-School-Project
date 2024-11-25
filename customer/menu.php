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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Our Menu</h1>
        <div class="row g-4">
        <?php if (!empty($sushiItems)): ?>
    <?php foreach ($sushiItems as $row): ?>
        <div class="col-md-4">
            <div class="menu-item card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">
                        <?php echo !empty($row['ItemName']) ? htmlspecialchars($row['ItemName']) : 'No Name Available'; ?>
                    </h2>
                    <p class="card-text">
                        <?php echo !empty($row['Description']) ? htmlspecialchars($row['Description']) : 'No Description Available'; ?>
                    </p>
                    <p class="card-text">
                        <strong>Price: $</strong>
                        <?php echo isset($row['Price']) ? number_format((float)$row['Price'], 2) : '0.00'; ?>
                    </p>
                    <p class="card-text">
                        <strong>Availability: </strong>
                        <?php
                        if (isset($row['AvailabilityStatus'])) {
                            echo $row['AvailabilityStatus'] == 1 ? 'Available' : 'Unavailable';
                        } else {
                            echo 'Unknown';
                        }
                        ?>
                    </p>
                    <?php if (isset($row['AvailabilityStatus']) && $row['AvailabilityStatus'] == 1): ?>
                        <a href="order.php?id=<?php echo isset($row['ItemID']) ? htmlspecialchars($row['ItemID']) : ''; ?>" 
                           class="btn btn-primary order-btn">
                           <i class="fas fa-shopping-cart"></i> Order Now
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Unavailable</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center">No items available at the moment.</p>
<?php endif; ?>

        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
