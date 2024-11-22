<?php
// In the login page, only process login without redirecting back to itself
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify credentials
    $_SESSION['admin_logged_in'] = true; // After successful verification
    header("Location: ../admin/index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Divine Sushi Shop Admin Dashboard</h1>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded shadow mb-5">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="manage_customers.php">Manage Customers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_orders.php">Manage Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_sushi.php">Manage Sushi Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Update Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card shadow p-4">
            <p class="text-muted text-center fs-5">Select a section to manage the sushi shop's data.</p>
        </div>
    </div>

      <!-- Charts Container -->
<div class="charts-container d-flex justify-content-center align-items-start gap-4 mt-5 mb-5">
    <!-- Chart Container: Top Selling Sushi Items -->
    <div class="card shadow p-4" style="max-width: 550px;">
        <h3 class="text-center mb-4">Top Selling Sushi Items</h3>
        <canvas id="topSellingChart"></canvas>
    </div>

    <!-- Chart Container: Total Revenue for the Month -->
    <div class="card shadow p-4" style="max-width: 350px;">
        <h3 class="text-center mb-4">Total Revenue for the Month</h3>
        <canvas id="monthlyRevenueChart" style="max-height: 300px;"></canvas>
    </div>
</div>

    <footer class="footer">
    <p>&copy; 2024 Divine Sushi | <a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
</footer>


    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
         // Fetch the sales data from the PHP backend
         fetch('fetch_top_selling.php')
            .then(response => response.json())
            .then(data => {
                // Extract labels and data for the chart
                const labels = data.map(item => item.name);
                const salesData = data.map(item => item.totalSold);

                // Create the chart
                const ctx = document.getElementById('topSellingChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Sold',
                            data: salesData,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Top 5 Best-Selling Sushi Items' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching sales data:', error));

            // Fetch data for monthly revenue
        fetch('fetch_monthly_revenue.php')
    .then(response => response.json())
    .then(data => {
        // Extract labels and data
        const labels = data.map(item => item.date);
        const revenueData = data.map(item => item.totalRevenue);

        // Create the revenue chart
        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    tension: 0.3 // Smooth the line
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Total Revenue for the Month' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => `$${value}` }
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error fetching revenue data:', error));

    </script>
</body>
</html>
