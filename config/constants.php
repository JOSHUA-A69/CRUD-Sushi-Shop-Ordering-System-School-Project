<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'Sushi_Shop');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// Site configuration
define('SITE_NAME', 'Divine Sushi Shop');
define('BASE_URL', 'http://localhost/IM101-FINALPROJECT');
define('MAX_DB_CONNECTIONS', 100);
define('LOG_PATH', __DIR__ . '/../logs');
define('LOG_LEVEL', 'ERROR');

// Session Configuration
define('SESSION_LIFETIME', 3600);
define('CSRF_TOKEN_NAME', 'csrf_token');

if (!file_exists(LOG_PATH)) {
    mkdir(LOG_PATH, 0755, true);
}

// Timezone configuration
define('TIMEZONE', 'Asia/Manila');

// Set default timezone
date_default_timezone_set(TIMEZONE);
?>
