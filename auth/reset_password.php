<?php
require_once '../config/database.php';

$email = $_GET['email'] ?? null;
if (!$email) {
    die("Invalid request.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        echo "<p>Passwords do not match. Please try again.</p>";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Update password in both tables
        $stmt = $mysqli->prepare("
            UPDATE customers SET password = ? WHERE email = ?
        ");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $stmt = $mysqli->prepare("
                UPDATE administrators SET password = ? WHERE email = ?
            ");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();
        }

        if ($stmt->affected_rows > 0) {
            echo "<p>Password reset successfully. You can now <a href='login.php'>login</a>.</p>";
        } else {
            echo "<p>Failed to reset password. Please try again later.</p>";
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<form action="reset_password.php?email=<?php echo htmlspecialchars($email); ?>" method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Reset Password</button>
</form>
