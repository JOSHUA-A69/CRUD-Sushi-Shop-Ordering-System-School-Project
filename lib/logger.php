<?php

require_once '../config/database.php';

class Logger {
    public static function error($message) {
        error_log("[ERROR] " . date('Y-m-d H:i:s') . " - " . $message);
    }
    
    public static function info($message) {
        if (LOG_LEVEL === 'INFO') {
            error_log("[INFO] " . date('Y-m-d H:i:s') . " - " . $message);
        }
    }
}

?>