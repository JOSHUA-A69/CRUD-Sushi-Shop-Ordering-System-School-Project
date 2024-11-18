<?php
session_start();
require_once '../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $orderID = $_POST['orderID'];
    $feedback = trim($_POST['feedback']);

    // Database connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare the query to update feedback
    $query = "UPDATE orders SET Feedback = ? WHERE orderID = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("si", $feedback, $orderID);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to submit feedback. Please try again.";
    }

    // Close connection
    $stmt->close();
    $mysqli->close();

    // Redirect back to the order history page
    header("Location: order_history.php");
    exit;
}
?>
