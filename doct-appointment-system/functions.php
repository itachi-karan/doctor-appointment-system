<?php
/**
 * Helper functions for the Doctor Appointment System
 */

/**
 * Get featured doctors from the database
 * @return array Array of featured doctors
 */
function get_featured_doctors() {
    global $db;
    try {
        $stmt = $db->prepare("
            SELECT id, first_name, last_name, specialty, bio, profile_image 
            FROM doctors 
            WHERE is_featured = 1 
            LIMIT 3
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching featured doctors: " . $e->getMessage());
        return [];
    }
}

/**
 * Set flash message to be displayed on the next page load
 * @param string $type Message type (success, danger, warning, info)
 * @param string $message The message to display
 */
function set_flash_message($type, $message) {
    if(!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][$type] = $message;
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is a doctor
 * @return bool True if user is a doctor, false otherwise
 */
function is_doctor() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor';
}

/**
 * Check if user is a patient
 * @return bool True if user is a patient, false otherwise
 */
function is_patient() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'patient';
}

/**
 * Sanitize input data
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format date to a readable format
 * @param string $date Date string
 * @param string $format Desired format (default: 'F j, Y')
 * @return string Formatted date
 */
function format_date($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Generate random token
 * @param int $length Length of token (default: 32)
 * @return string Random token
 */
function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Upload file
 * @param array $file $_FILES array element
 * @param string $destination Destination directory
 * @param array $allowed_types Array of allowed file types
 * @return string|false Filename if successful, false otherwise
 */
function upload_file($file, $destination, $allowed_types = ['jpg', 'jpeg', 'png']) {
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Check if file type is allowed
    if (!in_array($file_ext, $allowed_types)) {
        return false;
    }

    // Generate unique filename
    $new_filename = uniqid() . '.' . $file_ext;
    $target_path = $destination . '/' . $new_filename;

    // Move uploaded file
    if (move_uploaded_file($tmp_name, $target_path)) {
        return $new_filename;
    }

    return false;
}

/**
 * Send email
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email message
 * @param array $headers Additional headers
 * @return bool True if email sent successfully, false otherwise
 */
function send_email($to, $subject, $message, $headers = []) {
    $default_headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: DocAppointment <noreply@docappointment.com>'
    ];
    
    $headers = array_merge($default_headers, $headers);
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Get pagination data
 * @param int $total Total number of items
 * @param int $per_page Items per page
 * @param int $current_page Current page number
 * @return array Pagination data
 */
function get_pagination($total, $per_page, $current_page) {
    $total_pages = ceil($total / $per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $per_page;
    
    return [
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'per_page' => $per_page
    ];
}
?> 