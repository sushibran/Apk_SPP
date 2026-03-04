<?php
require_once 'config.php';
require_once 'utils.php';

// Export/Import endpoints
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'export-siswa') {
            exportSiswa();
        } elseif ($action === 'export-pembayaran') {
            exportPembayaran();
        } elseif ($action === 'export-spp') {
            exportSpp();
        } else {
            ApiResponse::sendResponse(
                ApiResponse::error('Export action not found', 404)
            );
        }
        break;

    case 'POST':
        if ($action === 'import-siswa') {
            importSiswa();
        } else {
            ApiResponse::sendResponse(
                ApiResponse::error('Import action not found', 404)
            );
        }
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function exportSiswa() {
    requireAdmin();
    
    $query = "SELECT s.*, k.nama_kelas, sp.tahun, sp.nominal 
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
              ORDER BY s.nisn";
    
    $data = queryAll($query);
    
    // Convert to CSV
    $csv = "NISN,NIS,Nama,Kelas,Tahun SPP,Nominal,Alamat,No Telp\n";
    foreach ($data as $row) {
        $csv .= '"' . implode('","', [
            $row['nisn'],
            $row['nis'],
            $row['nama'],
            $row['nama_kelas'],
            $row['tahun'],
            $row['nominal'],
            $row['alamat'],
            $row['no_telp']
        ]) . "\"\n";
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=siswa_' . date('Y-m-d') . '.csv');
    echo $csv;
    exit;
}

function exportPembayaran() {
    requireAdmin();
    
    $query = "SELECT p.*, s.nama, s.nisn, k.nama_kelas, sp.tahun, sp.nominal, pt.nama_petugas 
              FROM pembayaran p 
              JOIN siswa s ON p.nisn = s.nisn 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
              LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
              ORDER BY p.tgl_bayar DESC";
    
    $data = queryAll($query);
    
    // Convert to CSV
    $csv = "No Pembayaran,NISN,Nama,Kelas,Tanggal Bayar,Bulan Dibayar,Tahun,Nominal SPP,Jumlah Bayar,Petugas\n";
    foreach ($data as $row) {
        $csv .= '"' . implode('","', [
            $row['id_pembayaran'],
            $row['nisn'],
            $row['nama'],
            $row['nama_kelas'],
            $row['tgl_bayar'],
            $row['bulan_dibayar'],
            $row['tahun'],
            $row['nominal'],
            $row['jumlah_bayar'],
            $row['nama_petugas']
        ]) . "\"\n";
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=pembayaran_' . date('Y-m-d') . '.csv');
    echo $csv;
    exit;
}

function exportSpp() {
    requireAdmin();
    
    $query = "SELECT * FROM spp ORDER BY tahun DESC";
    $data = queryAll($query);
    
    // Convert to CSV
    $csv = "ID SPP,Tahun,Nominal\n";
    foreach ($data as $row) {
        $csv .= '"' . implode('","', [
            $row['id_spp'],
            $row['tahun'],
            $row['nominal']
        ]) . "\"\n";
    }
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=spp_' . date('Y-m-d') . '.csv');
    echo $csv;
    exit;
}

function importSiswa() {
    requireAdmin();
    
    if (!isset($_FILES['file'])) {
        ApiResponse::sendResponse(
            ApiResponse::error('File is required', 400)
        );
    }
    
    $file = $_FILES['file'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        ApiResponse::sendResponse(
            ApiResponse::error('File upload error', 400)
        );
    }
    
    $csv = array_map('str_getcsv', file($file['tmp_name']));
    array_shift($csv); // Remove header
    
    $imported = 0;
    $errors = [];
    
    foreach ($csv as $index => $row) {
        if (count($row) < 8) continue;
        
        $nisn = sanitize($row[0]);
        $nis = sanitize($row[1]);
        $nama = sanitize($row[2]);
        $id_kelas = sanitize($row[3]);
        $id_spp = sanitize($row[4]);
        $alamat = sanitize($row[5]);
        $no_telp = sanitize($row[6]);
        $password = hashPassword($row[7]); // Default password
        
        $query = "INSERT INTO siswa (nisn, nis, nama, id_kelas, id_spp, password, alamat, no_telp) 
                  VALUES ('$nisn', '$nis', '$nama', '$id_kelas', '$id_spp', '$password', '$alamat', '$no_telp')";
        
        if (execute($query)) {
            $imported++;
        } else {
            $errors[] = "Row " . ($index + 2) . ": " . getLastError();
        }
    }
    
    ApiResponse::sendResponse(
        ApiResponse::success([
            'imported' => $imported,
            'errors' => $errors
        ], 'Import completed', 200)
    );
}

?>
