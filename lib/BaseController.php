<?php

require_once '../config/database.php';

abstract class BaseController {
    protected function validateRequest($data, array $required = []) {
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new ValidationException("Missing required field: $field");
            }
        }
        return true;
    }
    
    protected function validatePermissions($userID, $requiredRole) {
        // Implement your permission checking logic here
        if (!SessionManager::hasRole($userID, $requiredRole)) {
            throw new AuthorizationException("Insufficient permissions");
        }
    }
    
    protected function respondSuccess($data = null, $message = 'Success') {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }
    
    protected function respondError($message = 'Error', $code = 400) {
        return [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];
    }
}

?>