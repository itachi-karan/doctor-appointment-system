<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'doc_appointment');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('SITE_NAME', 'Doctor Appointment System');
define('SITE_URL', 'http://localhost/doct-appointment-system');
define('ADMIN_EMAIL', 'admin@docappointment.com');

// Pagination settings
define('ITEMS_PER_PAGE', 10);
define('DOCTORS_PER_PAGE', 9);

// File upload settings
define('UPLOAD_PATH', __DIR__ . '/assets/uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
}

// Database connection
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}

// Time zone
date_default_timezone_set('UTC');

// Security headers
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
if(isset($_SERVER['HTTPS'])) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}
?> 