<?php
// In the login page, only process login without redirecting back to itself
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify credentials
    $_SESSION['customer_logged_in'] = true; // After successful verification
    header("Location: ../customer/index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sushi Shop - Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body>
    <!-- Background Image -->
    <div class="background-image"></div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="order_history.php">Order History</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Header Section -->
    <main class="header-content">
        <h1>Welcome to Divine Sushi Shop!</h1>
        <p class="lead">Explore our menu and enjoy the best sushi experience!</p>
        <a href="menu.php" class="btn btn-primary">View Menu</a>
    </main>

    <section class="top-selling-sushi mt-5">
    <div class="container mb-5 pb-5">
        <h2 class="text-center mb-4 custom-heading">Top 5 Best-Selling Sushi Items</h2>
        <div class="row g-4" id="topSellingSushi">
            <!-- Sushi items will be dynamically added here -->
        </div>
    </div>
</section>
 
    <footer class="footer">
    <p>&copy; 2024 Divine Sushi | <a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
</footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>

    // Fetch top-selling sushi items for customers
    fetch('fetch_top_selling.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('topSellingSushi');
                
                if (data.error) {
                    container.innerHTML = `<p class="text-danger text-center">${data.error}</p>`;
                    return;
                }

                // Check data structure
                console.log('Fetched Data:', data);

                data.forEach(item => {
                    const name = item.ItemName || 'Unknown Sushi';
                    const description = item.Description || 'No description available.';
                    const price = (item.Price !== undefined && item.Price !== null) ? `$${parseFloat(item.Price).toFixed(2)}` : 'Price not available';

                    console.log(`Item: ${name}, Price: ${price}`);  // Check individual item values

                    const sushiCard = `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title">${name}</h5>
                                    <p class="card-text">${description}</p>
                                    <p class="card-text"><strong>Price:</strong> ${price}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += sushiCard;
                });
            })
            .catch(error => {
                document.getElementById('topSellingSushi').innerHTML = `
                    <p class="text-danger text-center">Error loading top-selling sushi items: ${error.message}</p>
                `;
            });
</script>
</body>
</html>
