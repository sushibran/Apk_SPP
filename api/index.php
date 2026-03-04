<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL to determine the resource and method
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', trim($url, '/'));

// Get the resource (e.g., 'siswa', 'petugas', 'kelas', 'spp', 'pembayaran', 'auth')
$resource = isset($path[3]) ? $path[3] : '';

// Route to appropriate handler
switch ($resource) {
    case 'auth':
        require_once 'auth.php';
        break;
    
    case 'siswa':
        require_once 'siswa.php';
        break;
    
    case 'petugas':
        require_once 'petugas.php';
        break;
    
    case 'kelas':
        require_once 'kelas.php';
        break;
    
    case 'spp':
        require_once 'spp.php';
        break;
    
    case 'pembayaran':
        require_once 'pembayaran.php';
        break;
    
    case 'dashboard':
        require_once 'dashboard.php';
        break;
    
    case 'report':
        require_once 'report.php';
        break;
    
    case 'export':
    case 'import':
        require_once 'export.php';
        break;
    
    case '':
    case 'api':
        handleRoot();
        break;
    
    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Endpoint not found', 404)
        );
}

function handleRoot() {
    $api_version = '1.0.0';
    $endpoints = [
        'Authentication' => [
            'POST /api/auth/login' => 'Login as siswa or petugas',
            'POST /api/auth/logout' => 'Logout current user',
            'GET /api/auth/me' => 'Get current user data'
        ],
        'Siswa' => [
            'GET /api/siswa' => 'Get all siswa (admin only)',
            'GET /api/siswa/{nisn}' => 'Get siswa detail',
            'POST /api/siswa' => 'Create new siswa (admin only)',
            'PUT /api/siswa/{nisn}' => 'Update siswa (admin only)',
            'DELETE /api/siswa/{nisn}' => 'Delete siswa (admin only)'
        ],
        'Petugas' => [
            'GET /api/petugas' => 'Get all petugas (admin only)',
            'GET /api/petugas/{id}' => 'Get petugas detail (admin only)',
            'POST /api/petugas' => 'Create new petugas (admin only)',
            'PUT /api/petugas/{id}' => 'Update petugas (admin only)',
            'DELETE /api/petugas/{id}' => 'Delete petugas (admin only)'
        ],
        'Kelas' => [
            'GET /api/kelas' => 'Get all kelas',
            'GET /api/kelas/{id}' => 'Get kelas detail',
            'POST /api/kelas' => 'Create new kelas (admin only)',
            'PUT /api/kelas/{id}' => 'Update kelas (admin only)',
            'DELETE /api/kelas/{id}' => 'Delete kelas (admin only)'
        ],
        'SPP' => [
            'GET /api/spp' => 'Get all spp',
            'GET /api/spp/{id}' => 'Get spp detail',
            'POST /api/spp' => 'Create new spp (admin only)',
            'PUT /api/spp/{id}' => 'Update spp (admin only)',
            'DELETE /api/spp/{id}' => 'Delete spp (admin only)'
        ],
        'Pembayaran' => [
            'GET /api/pembayaran' => 'Get pembayaran (siswa sees own, petugas sees all)',
            'GET /api/pembayaran/{id}' => 'Get pembayaran detail',
            'POST /api/pembayaran' => 'Create new pembayaran (petugas only)',
            'PUT /api/pembayaran/{id}' => 'Update pembayaran (admin only)',
            'DELETE /api/pembayaran/{id}' => 'Delete pembayaran (admin only)'
        ],
        'Dashboard' => [
            'GET /api/dashboard' => 'Get dashboard statistics'
        ],
        'Reports' => [
            'GET /api/report?type=siswa-belum-lunas' => 'Get siswa belum lunas report',
            'GET /api/report?type=siswa-sudah-lunas' => 'Get siswa sudah lunas report',
            'GET /api/report?type=pembayaran-per-siswa&nisn=XXX' => 'Get pembayaran per siswa report',
            'GET /api/report?type=pembayaran-per-bulan&tahun=YYYY' => 'Get pembayaran per bulan report'
        ],
        'Export/Import' => [
            'GET /api/export?action=export-siswa' => 'Export siswa to CSV',
            'GET /api/export?action=export-pembayaran' => 'Export pembayaran to CSV',
            'GET /api/export?action=export-spp' => 'Export spp to CSV',
            'POST /api/export?action=import-siswa' => 'Import siswa from CSV (multipart form-data with file)'
        ]
    ];
    
    ApiResponse::sendResponse([
        'status' => 'success',
        'message' => 'SPP API Documentation',
        'version' => $api_version,
        'base_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/Apk_SPP/api',
        'endpoints' => $endpoints
    ]);
}

?>
