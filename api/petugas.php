<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL for ID
$id = isset($path[1]) ? $path[1] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            getPetugasDetail($id);
        } else {
            getAllPetugas();
        }
        break;

    case 'POST':
        createPetugas();
        break;

    case 'PUT':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        updatePetugas($id);
        break;

    case 'DELETE':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        deletePetugas($id);
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function getAllPetugas() {
    requireAdmin();
    
    $query = "SELECT id_petugas, username, nama_petugas, level FROM petugas ORDER BY id_petugas";
    $petugas = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($petugas, 'Petugas data retrieved', 200)
    );
}

function getPetugasDetail($id) {
    global $koneksi;
    
    $id = sanitize($id);
    
    $query = "SELECT id_petugas, username, nama_petugas, level FROM petugas WHERE id_petugas = '$id'";
    $petugas = queryFirst($query);
    
    if (!$petugas) {
        ApiResponse::sendResponse(
            ApiResponse::error('Petugas not found', 404)
        );
    }
    
    // Get payment count
    $payment_query = "SELECT COUNT(*) as jumlah FROM pembayaran WHERE id_petugas = '$id'";
    $payment = queryFirst($payment_query);
    $petugas['jumlah_pembayaran'] = $payment['jumlah'];
    
    ApiResponse::sendResponse(
        ApiResponse::success($petugas, 'Petugas detail retrieved', 200)
    );
}

function createPetugas() {
    global $koneksi, $data;
    
    requireAdmin();
    
    $required = ['username', 'password', 'nama_petugas', 'level'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            ApiResponse::sendResponse(
                ApiResponse::error("Field '$field' is required", 400)
            );
        }
    }
    
    // Validate level
    if (!in_array($data['level'], ['admin', 'petugas'])) {
        ApiResponse::sendResponse(
            ApiResponse::error('Invalid level. Must be admin or petugas', 400)
        );
    }
    
    $username = sanitize($data['username']);
    $password = hashPassword($data['password']);
    $nama_petugas = sanitize($data['nama_petugas']);
    $level = sanitize($data['level']);
    
    $query = "INSERT INTO petugas (username, password, nama_petugas, level) 
              VALUES ('$username', '$password', '$nama_petugas', '$level')";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to create petugas: ' . getLastError(), 400)
        );
    }
    
    $id = getLastId();
    ApiResponse::sendResponse(
        ApiResponse::success(['id_petugas' => $id], 'Petugas created successfully', 201)
    );
}

function updatePetugas($id) {
    global $koneksi, $data;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if petugas exists
    if (!queryFirst("SELECT * FROM petugas WHERE id_petugas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Petugas not found', 404)
        );
    }
    
    $updates = [];
    $fields = ['username', 'nama_petugas', 'level'];
    
    foreach ($fields as $field) {
        if (isset($data[$field]) && $data[$field] !== '') {
            if ($field === 'level' && !in_array($data[$field], ['admin', 'petugas'])) {
                ApiResponse::sendResponse(
                    ApiResponse::error('Invalid level. Must be admin or petugas', 400)
                );
            }
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
    
    $query = "UPDATE petugas SET " . implode(', ', $updates) . " WHERE id_petugas = '$id'";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to update petugas: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Petugas updated successfully', 200)
    );
}

function deletePetugas($id) {
    global $koneksi;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if petugas exists
    if (!queryFirst("SELECT * FROM petugas WHERE id_petugas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Petugas not found', 404)
        );
    }
    
    if (!execute("DELETE FROM petugas WHERE id_petugas = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to delete petugas: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'Petugas deleted successfully', 200)
    );
}

?>
