<?php
require_once 'config.php';
require_once 'utils.php';

// Parse URL for ID
$id = isset($path[4]) ? $path[4] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            getSppDetail($id);
        } else {
            getAllSpp();
        }
        break;

    case 'POST':
        createSpp();
        break;

    case 'PUT':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        updateSpp($id);
        break;

    case 'DELETE':
        if (!$id) {
            ApiResponse::sendResponse(
                ApiResponse::error('ID is required', 400)
            );
        }
        deleteSpp($id);
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function getAllSpp() {
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Unauthorized', 401)
        );
    }
    
    $query = "SELECT * FROM spp ORDER BY tahun DESC";
    $spp = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($spp, 'SPP data retrieved', 200)
    );
}

function getSppDetail($id) {
    global $koneksi;
    
    $id = sanitize($id);
    
    $query = "SELECT * FROM spp WHERE id_spp = '$id'";
    $spp = queryFirst($query);
    
    if (!$spp) {
        ApiResponse::sendResponse(
            ApiResponse::error('SPP not found', 404)
        );
    }
    
    // Get count of students with this SPP
    $count_query = "SELECT COUNT(*) as jumlah FROM siswa WHERE id_spp = '$id'";
    $count = queryFirst($count_query);
    $spp['jumlah_siswa'] = $count['jumlah'];
    
    // Get total paid and pending
    $paid_query = "SELECT COUNT(*) as terbayar, SUM(jumlah_bayar) as total_bayar 
                   FROM pembayaran WHERE id_spp = '$id'";
    $paid = queryFirst($paid_query);
    $spp['total_terbayar'] = $paid['total_bayar'] ?? 0;
    $spp['jumlah_pembayaran'] = $paid['terbayar'];
    
    ApiResponse::sendResponse(
        ApiResponse::success($spp, 'SPP detail retrieved', 200)
    );
}

function createSpp() {
    global $koneksi, $data;
    
    requireAdmin();
    
    $required = ['tahun', 'nominal'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            ApiResponse::sendResponse(
                ApiResponse::error("Field '$field' is required", 400)
            );
        }
    }
    
    $tahun = sanitize($data['tahun']);
    $nominal = sanitize($data['nominal']);
    
    $query = "INSERT INTO spp (tahun, nominal) 
              VALUES ('$tahun', '$nominal')";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to create spp: ' . getLastError(), 400)
        );
    }
    
    $id = getLastId();
    ApiResponse::sendResponse(
        ApiResponse::success(['id_spp' => $id], 'SPP created successfully', 201)
    );
}

function updateSpp($id) {
    global $koneksi, $data;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if spp exists
    if (!queryFirst("SELECT * FROM spp WHERE id_spp = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('SPP not found', 404)
        );
    }
    
    $updates = [];
    $fields = ['tahun', 'nominal'];
    
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
    
    $query = "UPDATE spp SET " . implode(', ', $updates) . " WHERE id_spp = '$id'";
    
    if (!execute($query)) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to update spp: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'SPP updated successfully', 200)
    );
}

function deleteSpp($id) {
    global $koneksi;
    
    requireAdmin();
    
    $id = sanitize($id);
    
    // Check if spp exists
    if (!queryFirst("SELECT * FROM spp WHERE id_spp = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('SPP not found', 404)
        );
    }
    
    // Check if there are students or payments with this SPP
    $spp_count = queryFirst("SELECT COUNT(*) as jumlah FROM siswa WHERE id_spp = '$id'");
    if ($spp_count['jumlah'] > 0) {
        ApiResponse::sendResponse(
            ApiResponse::error('Cannot delete SPP with assigned students', 400)
        );
    }
    
    if (!execute("DELETE FROM spp WHERE id_spp = '$id'")) {
        ApiResponse::sendResponse(
            ApiResponse::error('Failed to delete spp: ' . getLastError(), 400)
        );
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success(null, 'SPP deleted successfully', 200)
    );
}

?>
