<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL for ID
$id = isset($path[4]) ? $path[4] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            getKelasDetail($id);
        } else {
            getAllKelas();
        }
        break;

    case 'POST':
        createKelas();
        break;

    case 'PUT':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        updateKelas($id);
        break;

    case 'DELETE':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        deleteKelas($id);
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function getAllKelas() {
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Unauthorized', 401)
        );
    }
    
    $query = "SELECT * FROM kelas ORDER BY id_kelas ASC";
    $kelas = queryAll($query);
    
    // Add student count for each class
    foreach ($kelas as &$k) {
        $count_query = "SELECT COUNT(*) as jumlah FROM siswa WHERE id_kelas = " . $k['id_kelas'];
        $count = queryFirst($count_query);
        $k['jumlah_siswa'] = $count['jumlah'];
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success($kelas, 'Kelas data retrieved', 200)
    );
}

function getKelasDetail($id) {
    global $koneksi;
    
    $id = sanitize($id);
    
    $query = "SELECT * FROM kelas WHERE id_kelas = '$id'";
    $kelas = queryFirst($query);
    
    if (!$kelas) {
        ApiResponse::sendResponse(
            ApiResponse::error('Kelas not found', 404)
        );
    }
    
    // Get list of students in this class
    $siswa_query = "SELECT nisn, nama, nis FROM siswa WHERE id_kelas = '$id' ORDER BY nama";
    $kelas['siswa'] = queryAll($siswa_query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($kelas, 'Kelas detail retrieved', 200)
    );
}

function createKelas() {
    global $koneksi, $data;
    
    requireAdmin();
    
    $required = ['nama_kelas', 'kompetensi_keahlian'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            ApiResponse::sendResponse(
                ApiResponse::error("Field '$field' is required", 400)
            );
        }
    }
    
    $nama_kelas = sanitize($data['nama_kelas']);
    $kompetensi_keahlian = sanitize($data['kompetensi_keahlian']);
    
    $query = "INSERT INTO kelas (nama_kelas, kompetensi_keahlian) 
              VALUES ('$nama_kelas', '$kompetensi_keahlian')";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to create kelas: ' . getLastError(), 400)
        );
    }
    
    $id = getLastId();
    ApiResponse::sendResponse(
        ApiResponse::success(['id_kelas' => $id], 'Kelas created successfully', 201)
    );
}

function updateKelas($id) {
    global $koneksi, $data;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if kelas exists
    if (!queryFirst("SELECT * FROM kelas WHERE id_kelas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Kelas not found', 404)
        );
    }
    
    $updates = [];
    $fields = ['nama_kelas', 'kompetensi_keahlian'];
    
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
    
    $query = "UPDATE kelas SET " . implode(', ', $updates) . " WHERE id_kelas = '$id'";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to update kelas: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Kelas updated successfully', 200)
    );
}

function deleteKelas($id) {
    global $koneksi;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if kelas exists
    if (!queryFirst("SELECT * FROM kelas WHERE id_kelas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Kelas not found', 404)
        );
    }
    
    // Check if there are students in this class
    $count = queryFirst("SELECT COUNT(*) as jumlah FROM siswa WHERE id_kelas = '$id'");
    if ($count['jumlah'] > 0) {
        ApiResponse::sendResponse(
            ApiResponse::error('Cannot delete kelas with students', 400)
        );
    }
    
    if (!execute("DELETE FROM kelas WHERE id_kelas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to delete kelas: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Kelas deleted successfully', 200)
    );
}

?>
