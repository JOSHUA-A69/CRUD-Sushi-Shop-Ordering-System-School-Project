<?php

require_once '../config/database.php';

/*class SessionManager {
    public static function hasRole($userID, $requiredRole) {
       
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Check if the fetched role matches the required role
        return ($user && $user['role'] === $requiredRole);
    }
}
?>

?>