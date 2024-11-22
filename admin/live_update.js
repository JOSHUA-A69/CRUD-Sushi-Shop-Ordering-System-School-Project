setInterval(() => {
    fetch('fetch_top_selling.php')
        .then(response => response.json())
        .then(data => {
            topSellingChart.data.labels = data.map(item => item.name);
            topSellingChart.data.datasets[0].data = data.map(item => item.totalSold);
            topSellingChart.update();
        });
}, 10000); // Refresh every 10 seconds


fetch('fetch_monthly_revenue.php')
    .then(response => response.json())
    .then(data => {
        console.log('Revenue Data:', data); // Log fetched data
        const labels = data.map(item => item.date);
        const revenueData = data.map(item => item.totalRevenue);

        console.log('Labels:', labels); // Log labels
        console.log('Revenue Data for Chart:', revenueData); // Log data

        // Update chart data
        monthlyRevenueChart.data.labels = labels;
        monthlyRevenueChart.data.datasets[0].data = revenueData;
        monthlyRevenueChart.update();
    })
    .catch(error => console.error('Error updating revenue chart:', error));
