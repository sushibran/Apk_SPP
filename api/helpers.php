<?php
/**
 * SPP API Helpers
 * Utility functions for API operations
 */

/**
 * Validate if a file is a valid CSV
 */
function isValidCSV($file) {
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    return $ext === 'csv' && $file['type'] === 'text/csv';
}

/**
 * Validate email format
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number format
 */
function isValidPhoneNumber($phone) {
    return preg_match('/^(\+62|0)[0-9]{9,12}$/', $phone);
}

/**
 * Validate date format (YYYY-MM-DD)
 */
function isValidDate($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date) !== false;
}

/**
 * Validate NISN format (10 digits)
 */
function isValidNISN($nisn) {
    return preg_match('/^[0-9]{10}$/', $nisn);
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.');
}

/**
 * Format date
 */
function formatDate($date, $format = 'd-m-Y') {
    return date($format, strtotime($date));
}

/**
 * Get month name
 */
function getMonthName($month_number) {
    $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    
    $month_name = date('F', mktime(0, 0, 0, $month_number, 1));
    return $months[$month_name] ?? $month_name;
}

/**
 * Calculate percentage
 */
function calculatePercentage($current, $total) {
    if ($total == 0) return 0;
    return round(($current / $total) * 100, 2);
}

/**
 * Log API activity
 */
function logActivity($user_id, $action, $resource, $details = null) {
    global $koneksi;
    
    $user_id = sanitize($user_id);
    $action = sanitize($action);
    $resource = sanitize($resource);
    $details = sanitize($details);
    $timestamp = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Note: You would need to create an activity_log table first
    // INSERT INTO activity_log (user_id, action, resource, details, timestamp, ip_address)
}

/**
 * Send email notification
 */
function sendEmailNotification($email, $subject, $body) {
    // This is a basic example - you might want to use SwiftMailer or similar
    $headers = "From: noreply@spp.local\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($email, $subject, $body, $headers);
}

/**
 * Generate random token
 */
function generateRandomToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Retry database query
 */
function retryQuery($query, $max_retries = 3, $delay = 1000) {
    global $koneksi;
    
    for ($i = 0; $i < $max_retries; $i++) {
        $result = mysqli_query($koneksi, $query);
        
        if ($result !== false) {
            return $result;
        }
        
        if ($i < $max_retries - 1) {
            usleep($delay * 1000);
        }
    }
    
    return false;
}

?>
