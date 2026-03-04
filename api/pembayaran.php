<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL for ID
$id = isset($path[4]) ? $path[4] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            getPembayaranDetail($id);
        } else {
            getAllPembayaran();
        }
        break;

    case 'POST':
        createPembayaran();
        break;

    case 'PUT':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        updatePembayaran($id);
        break;

    case 'DELETE':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        deletePembayaran($id);
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function getAllPembayaran() {
    global $koneksi;
    
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Unauthorized', 401)
        );
    }
    
    // Students can only see their own payments
    if ($user['type'] === 'siswa') {
        $nisn = $user['id'];
        $query = "SELECT p.*, s.nama, s.nisn, k.nama_kelas, sp.tahun, sp.nominal, pt.nama_petugas 
                  FROM pembayaran p 
                  JOIN siswa s ON p.nisn = s.nisn 
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                  LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
                  LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
                  WHERE p.nisn = '$nisn' 
                  ORDER BY p.tgl_bayar DESC";
    } else {
        // Petugas can see all or filtered payments
        $filter = '';
        if (isset($_GET['nisn'])) {
            $nisn = sanitize($_GET['nisn']);
            $filter = " WHERE p.nisn = '$nisn'";
        }
        
        $query = "SELECT p.*, s.nama, s.nisn, k.nama_kelas, sp.tahun, sp.nominal, pt.nama_petugas 
                  FROM pembayaran p 
                  JOIN siswa s ON p.nisn = s.nisn 
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                  LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
                  LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
                  $filter 
                  ORDER BY p.tgl_bayar DESC";
    }
    
    $pembayaran = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($pembayaran, 'Pembayaran data retrieved', 200)
    );
}

function getPembayaranDetail($id) {
    global $koneksi;
    
    $id = sanitize($id);
    
    $query = "SELECT p.*, s.nama, s.nisn, k.nama_kelas, sp.tahun, sp.nominal, pt.nama_petugas 
              FROM pembayaran p 
              JOIN siswa s ON p.nisn = s.nisn 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
              LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
              WHERE p.id_pembayaran = '$id'";
    
    $pembayaran = queryFirst($query);
    
    if (!$pembayaran) {
        ApiResponse::sendResponse(
            ApiResponse::error('Pembayaran not found', 404)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success($pembayaran, 'Pembayaran detail retrieved', 200)
    );
}

function createPembayaran() {
    global $koneksi, $data;
    
    // Require petugas role
    $user = requireAuth();
    if ($user['type'] !== 'petugas') {
        ApiResponse::sendResponse(
            ApiResponse::error('Only petugas can create payments', 403)
        );
    }
    
    $required = ['nisn', 'id_spp', 'jumlah_bayar', 'bulan_dibayar'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            ApiResponse::sendResponse(
                ApiResponse::error("Field '$field' is required", 400)
            );
        }
    }
    
    $id_petugas = $user['id'];
    $nisn = sanitize($data['nisn']);
    $id_spp = sanitize($data['id_spp']);
    $jumlah_bayar = sanitize($data['jumlah_bayar']);
    $bulan_dibayar = sanitize($data['bulan_dibayar']);
    $tahun_dibayar = sanitize($data['tahun_dibayar'] ?? date('Y'));
    $tgl_bayar = sanitize($data['tgl_bayar'] ?? date('Y-m-d'));
    
    // Verify siswa exists
    if (!queryFirst("SELECT * FROM siswa WHERE nisn = '$nisn'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Siswa not found', 404)
        );
    }
    
    $query = "INSERT INTO pembayaran (id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar) 
              VALUES ('$id_petugas', '$nisn', '$tgl_bayar', '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$jumlah_bayar')";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to create pembayaran: ' . getLastError(), 400)
        );
    }
    
    $id = getLastId();
    ApiResponse::sendResponse(
        ApiResponse::success(['id_pembayaran' => $id], 'Pembayaran created successfully', 201)
    );
}

function updatePembayaran($id) {
    global $koneksi, $data;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if pembayaran exists
    if (!queryFirst("SELECT * FROM pembayaran WHERE id_pembayaran = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Pembayaran not found', 404)
        );
    }
    
    $updates = [];
    $fields = ['nisn', 'id_spp', 'jumlah_bayar', 'bulan_dibayar', 'tahun_dibayar', 'tgl_bayar'];
    
    foreach ($fields as $field) {
        if (isset($data[$field]) && $data[$field] !== '') {
            $value = sanitize($data[$field]);
            $updates[] = "$field = '$value'";
        }
    }
    
    if (empty($updates)) {
        ApiResponse::sendResponse(
            ApiResponse::error('No fields to update', 400)
        );
    }
    
    $query = "UPDATE pembayaran SET " . implode(', ', $updates) . " WHERE id_pembayaran = '$id'";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to update pembayaran: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Pembayaran updated successfully', 200)
    );
}

function deletePembayaran($id) {
    global $koneksi;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if pembayaran exists
    if (!queryFirst("SELECT * FROM pembayaran WHERE id_pembayaran = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Pembayaran not found', 404)
        );
    }
    
    if (!execute("DELETE FROM pembayaran WHERE id_pembayaran = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to delete pembayaran: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Pembayaran deleted successfully', 200)
    );
}

?>
