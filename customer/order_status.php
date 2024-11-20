<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php
        // Retrieve status and message from query parameters
        $status = $_GET['status'] ?? 'failure';
        $message = $_GET['message'] ?? 'Something went wrong. Please try again.';

        if ($status === 'success') {
            echo '<div class="alert alert-success text-center mt-4">
                    <p>' . htmlspecialchars($message) . '</p>
                    <a href="index.php" class="btn btn-primary mt-2">Back to Dashboard</a>
                  </div>';
        } else {
            echo '<div class="alert alert-danger text-center mt-4">
                    <p>' . htmlspecialchars($message) . '</p>
                    <a href="index.php" class="btn btn-primary mt-2">Back to Dashboard</a>
                  </div>';
        }
        ?>
    </div>
</body>
</html>
