<?php

require_once '../config/database.php';
class Logger {
    // Log messages to a log file with timestamps
    public static function log($level, $message) {
        $logFile = __DIR__ . '/app.log'; 
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $level: $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    // Log error messages
    public static function error($message) {
        self::log('ERROR', $message);
    }

    // Log info messages
    public static function info($message) {
        self::log('INFO', $message);
    }
}

?>