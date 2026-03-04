<?php
// API Configuration
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database connection
$koneksi = mysqli_connect('localhost', 'root', '', 'db_spp');

if (!$koneksi) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . mysqli_connect_error()
    ]);
    exit;
}

// Set charset to utf8
mysqli_set_charset($koneksi, "utf8");

// API Response class
class ApiResponse {
    public static function success($data = null, $message = 'Success', $code = 200) {
        http_response_code($code);
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error($message = 'Error', $code = 400, $data = null) {
        http_response_code($code);
        return [
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ];
    }

    public static function sendResponse($response) {
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request path
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', $url);

// Get body data
$input = file_get_contents('php://input');
$data = json_decode($input, true) ?? $_POST;

// Start session
session_start();

?>
