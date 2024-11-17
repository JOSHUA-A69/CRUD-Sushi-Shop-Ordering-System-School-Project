<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT email FROM customers WHERE email = ? UNION SELECT email FROM administrators WHERE email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to reset password page with the email
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();
    } else {
        echo "<p>This email is not registered. Please check and try again.</p>";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<form action="forgot_password.php" method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Request Reset</button>
</form>
