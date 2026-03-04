<?php
require_once 'config.php';
require_once 'utils.php';

// Report endpoints
$type = isset($_GET['type']) ? $_GET['type'] : '';

switch ($method) {
    case 'GET':
        if ($type === 'siswa-belum-lunas') {
            reportSiswaBelumLunas();
        } elseif ($type === 'siswa-sudah-lunas') {
            reportSiswaSudahLunas();
        } elseif ($type === 'pembayaran-per-siswa') {
            reportPembayaranPerSiswa();
        } elseif ($type === 'pembayaran-per-bulan') {
            reportPembayaranPerBulan();
        } else {
            ApiResponse::sendResponse(
                ApiResponse::error('Report type not found', 404)
            );
        }
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function reportSiswaBelumLunas() {
    requireAdmin();
    
    $query = "SELECT s.nisn, s.nama, s.nis, k.nama_kelas, sp.nominal, 
                     COALESCE(SUM(p.jumlah_bayar), 0) as total_bayar, 
                     (sp.nominal - COALESCE(SUM(p.jumlah_bayar), 0)) as sisa_bayar
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
              LEFT JOIN pembayaran p ON s.nisn = p.nisn 
              GROUP BY s.nisn, s.nama, s.nis, k.nama_kelas, sp.nominal 
              HAVING sisa_bayar > 0 
              ORDER BY sisa_bayar DESC";
    
    $data = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($data, 'Siswa belum lunas report', 200)
    );
}

function reportSiswaSudahLunas() {
    requireAdmin();
    
    $query = "SELECT s.nisn, s.nama, s.nis, k.nama_kelas, sp.nominal, 
                     COALESCE(SUM(p.jumlah_bayar), 0) as total_bayar, 
                     MAX(p.tgl_bayar) as tgl_selesai
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
              LEFT JOIN pembayaran p ON s.nisn = p.nisn 
              GROUP BY s.nisn, s.nama, s.nis, k.nama_kelas, sp.nominal 
              HAVING COALESCE(SUM(p.jumlah_bayar), 0) >= COALESCE(sp.nominal, 0) 
              ORDER BY tgl_selesai DESC";
    
    $data = queryAll($query);
    
    ApiResponse::sendResponse(
        ApiResponse::success($data, 'Siswa sudah lunas report', 200)
    );
}

function reportPembayaranPerSiswa() {
    requireAdmin();
    
    $nisn = isset($_GET['nisn']) ? sanitize($_GET['nisn']) : null;
    
    if (!$nisn) {
        ApiResponse::sendResponse(
            ApiResponse::error('NISN parameter is required', 400)
        );
    }
    
    $query = "SELECT p.id_pembayaran, p.tgl_bayar, p.bulan_dibayar, p.tahun_dibayar, 
                     p.jumlah_bayar, sp.nominal, pt.nama_petugas, sp.tahun,
                     COALESCE(SUM(pp.jumlah_bayar), 0) as total_sebelumnya
              FROM pembayaran p 
              LEFT JOIN spp sp ON p.id_spp = sp.id_spp 
              LEFT JOIN petugas pt ON p.id_petugas = pt.id_petugas 
              LEFT JOIN pembayaran pp ON p.nisn = pp.nisn AND pp.id_pembayaran < p.id_pembayaran
              WHERE p.nisn = '$nisn'
              GROUP BY p.id_pembayaran
              ORDER BY p.tgl_bayar DESC";
    
    $data = queryAll($query);
    
    // Get siswa data
    $siswa_query = "SELECT s.*, k.nama_kelas, sp.nominal 
                    FROM siswa s 
                    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
                    LEFT JOIN spp sp ON s.id_spp = sp.id_spp 
                    WHERE s.nisn = '$nisn'";
    $siswa = queryFirst($siswa_query);
    
    // Calculate totals
    $total_query = "SELECT COUNT(*) as jumlah_pembayaran, SUM(jumlah_bayar) as total_bayar 
                    FROM pembayaran WHERE nisn = '$nisn'";
    $totals = queryFirst($total_query);
    
    $result = [
        'siswa' => $siswa,
        'pembayaran' => $data,
        'ringkasan' => [
            'jumlah_pembayaran' => $totals['jumlah_pembayaran'],
            'total_bayar' => $totals['total_bayar'] ?? 0,
            'sisa_bayar' => ($siswa['nominal'] ?? 0) - ($totals['total_bayar'] ?? 0)
        ]
    ];
    
    ApiResponse::sendResponse(
        ApiResponse::success($result, 'Pembayaran per siswa report', 200)
    );
}

function reportPembayaranPerBulan() {
    requireAdmin();
    
    $tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
    
    $query = "SELECT bulan_dibayar, tahun_dibayar, COUNT(*) as jumlah_pembayaran, 
                     SUM(jumlah_bayar) as total_bayar, COUNT(DISTINCT nisn) as jumlah_siswa
              FROM pembayaran 
              WHERE tahun_dibayar = '$tahun'
              GROUP BY tahun_dibayar, bulan_dibayar, DATE(STR_TO_DATE(CONCAT(tahun_dibayar, '-01-', bulan_dibayar), '%Y-%d-%B'))
              ORDER BY MONTH(STR_TO_DATE(bulan_dibayar, '%B')) ASC";
    
    $data = queryAll($query);
    
    // Get total for the year
    $total_query = "SELECT COUNT(*) as total_pembayaran, SUM(jumlah_bayar) as total_bayar, 
                           COUNT(DISTINCT nisn) as total_siswa
                    FROM pembayaran WHERE tahun_dibayar = '$tahun'";
    $totals = queryFirst($total_query);
    
    $result = [
        'tahun' => $tahun,
        'data_bulanan' => $data,
        'ringkasan' => [
            'total_pembayaran' => $totals['total_pembayaran'],
            'total_bayar' => $totals['total_bayar'] ?? 0,
            'total_siswa' => $totals['total_siswa']
        ]
    ];
    
    ApiResponse::sendResponse(
        ApiResponse::success($result, 'Pembayaran per bulan report', 200)
    );
}

?>
