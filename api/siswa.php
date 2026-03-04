<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL for ID
$id = isset($path[4]) ? $path[4] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            getSiswaDetail($id);
        } else {
            getAllSiswa();
        }
        break;

    case 'POST':
        createSiswa();
        break;

    case 'PUT':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        updateSiswa($id);
        break;

    case 'DELETE':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        deleteSiswa($id);
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function getAllSiswa() {
    global $koneksi;
    
    // Check if user has permission
    $user = getAuthUser();
    if (!$user || ($user['type'] === 'petugas' && $user['level'] !== 'admin')) {
        ApiResponse::sendResponse(
            ApiResponse::error('Forbidden', 403)
        );
    }

    $query = "SELECT s.*, k.nama_kelas, sp.tahun, sp.nominal 
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
              ORDER BY s.nisn";
    
    $siswa = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($siswa, 'Siswa data retrieved', 200)
    );
}

function getSiswaDetail($nisn) {
    global $koneksi;
    
    $nisn = sanitize($nisn);
    $query = "SELECT s.*, k.nama_kelas, sp.tahun, sp.nominal 
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
              WHERE s.nisn = '$nisn'";
    
    $siswa = queryFirst($query);
    
    if (!$siswa) {
        ApiResponse::sendResponse(
            ApiResponse::error('Siswa not found', 404)
        );
    }
    
    // Get payment history
    $payment_query = "SELECT p.*, pt.nama_petugas, sp.tahun, sp.nominal 
                      FROM pembayaran p 
                      LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
                      LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
                      WHERE p.nisn = '$nisn' 
                      ORDER BY p.tgl_bayar DESC";
    
    $siswa['pembayaran'] = queryAll($payment_query);
    
    // Get total payment and remaining
    $total_query = "SELECT SUM(jumlah_bayar) as total_bayar FROM pembayaran WHERE nisn = '$nisn'";
    $total = queryFirst($total_query);
    $siswa['total_bayar'] = $total['total_bayar'] ?? 0;
    $siswa['sisa_bayar'] = ($siswa['nominal'] ?? 0) - ($siswa['total_bayar'] ?? 0);
    
    ApiResponse::sendResponse(
        ApiResponse::success($siswa, 'Siswa detail retrieved', 200)
    );
}

function createSiswa() {
    global $koneksi, $data;
    
    requireAdmin();
    
    $required = ['nisn', 'nis', 'nama', 'id_kelas', 'id_spp', 'password', 'alamat', 'no_telp'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            ApiResponse::sendResponse(
                ApiResponse::error("Field '$field' is required", 400)
            );
        }
    }
    
    $nisn = sanitize($data['nisn']);
    $nis = sanitize($data['nis']);
    $nama = sanitize($data['nama']);
    $id_kelas = sanitize($data['id_kelas']);
    $id_spp = sanitize($data['id_spp']);
    $password = hashPassword($data['password']);
    $alamat = sanitize($data['alamat']);
    $no_telp = sanitize($data['no_telp']);
    
    $query = "INSERT INTO siswa (nisn, nis, nama, id_kelas, id_spp, password, alamat, no_telp) 
              VALUES ('$nisn', '$nis', '$nama', '$id_kelas', '$id_spp', '$password', '$alamat', '$no_telp')";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to create siswa: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(['nisn' => $nisn], 'Siswa created successfully', 201)
    );
}

function updateSiswa($nisn) {
    global $koneksi, $data;
    
    requireAdmin();
    
    $nisn = sanitize($nisn);
    
    // Check if siswa exists
    if (!queryFirst("SELECT * FROM siswa WHERE nisn = '$nisn'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Siswa not found', 404)
        );
    }
    
    $updates = [];
    $fields = ['nis', 'nama', 'id_kelas', 'id_spp', 'alamat', 'no_telp'];
    
    foreach ($fields as $field) {
        if (isset($data[$field]) && $data[$field] !== '') {
            $value = sanitize($data[$field]);
            $updates[] = "$field = '$value'";
        }
    }
    
    if (isset($data['password']) && $data['password'] !== '') {
        $password = hashPassword($data['password']);
        $updates[] = "password = '$password'";
    }
    
    if (empty($updates)) {
        ApiResponse::sendResponse(
            ApiResponse::error('No fields to update', 400)
        );
    }
    
    $query = "UPDATE siswa SET " . implode(', ', $updates) . " WHERE nisn = '$nisn'";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to update siswa: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Siswa updated successfully', 200)
    );
}

function deleteSiswa($nisn) {
    global $koneksi;
    
    requireAdmin();
    
    $nisn = sanitize($nisn);
    
    // Check if siswa exists
    if (!queryFirst("SELECT * FROM siswa WHERE nisn = '$nisn'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Siswa not found', 404)
        );
    }
    
    // Delete related payments first
    execute("DELETE FROM pembayaran WHERE nisn = '$nisn'");
    
    // Delete siswa
    if (!execute("DELETE FROM siswa WHERE nisn = '$nisn'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to delete siswa: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Siswa deleted successfully', 200)
    );
}

?>
