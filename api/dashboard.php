<?php
require_once 'config.php';
require_once 'utils.php';

// Dashboard/Statistics endpoint
switch ($method) {
    case 'GET':
        handleDashboard();
        break;

    default:
        ApiResponse::sendResponse(
            ApiResponse::error('Method not allowed', 405)
        );
}

function handleDashboard()
{
    $user = getAuthUser();
    if (!$user) {
        ApiResponse::sendResponse(
            ApiResponse::error('Unauthorized', 401)
        );
    }

    $stats = [];

    // Total siswa
    $total_siswa = queryFirst("SELECT COUNT(*) as total FROM siswa");
    $stats['total_siswa'] = $total_siswa['total'];

    // Total kelas
    $total_kelas = queryFirst("SELECT COUNT(*) as total FROM kelas");
    $stats['total_kelas'] = $total_kelas['total'];

    // Total SPP nominal
    $total_spp = queryFirst("SELECT SUM(nominal) as total FROM spp");
    $stats['total_spp_nominal'] = $total_spp['total'] ?? 0;

    // Total pembayaran
    $total_pembayaran = queryFirst("SELECT COUNT(*) as total, SUM(jumlah_bayar) as total_bayar FROM pembayaran");
    $stats['total_pembayaran_count'] = $total_pembayaran['total'];
    $stats['total_pembayaran_nominal'] = $total_pembayaran['total_bayar'] ?? 0;

    // Total potensi bayar (jumlah siswa x nominal SPP rata-rata)
    $avg_spp = queryFirst("SELECT AVG(nominal) as avg FROM spp");
    $stats['total_potensi'] = $stats['total_siswa'] * ($avg_spp['avg'] ?? 0);

    // Belum lunas
    $belum_lunas = queryFirst("
    SELECT COUNT(*) as total
    FROM (
        SELECT 
            s.nisn,
            sp.nominal,
            COALESCE(SUM(p.jumlah_bayar),0) AS total_bayar
        FROM siswa s
        LEFT JOIN pembayaran p ON s.nisn = p.nisn
        LEFT JOIN spp sp ON s.id_spp = sp.id_spp
        GROUP BY s.nisn, sp.nominal
        HAVING total_bayar < nominal
    ) t
");
    $stats['belum_lunas'] = $belum_lunas['total'] ?? 0;

    // Sudah lunas
    $sudah_lunas = $stats['total_siswa'] - $stats['belum_lunas'];
    $stats['sudah_lunas'] = max(0, $sudah_lunas);

    // Top 5 Pembayaran terbesar
    $top_pembayaran = queryAll("
        SELECT p.id_pembayaran, p.jumlah_bayar, s.nama, p.tgl_bayar 
        FROM pembayaran p 
        JOIN siswa s ON p.nisn = s.nisn 
        ORDER BY p.jumlah_bayar DESC 
        LIMIT 5
    ");
    $stats['top_pembayaran'] = $top_pembayaran;

    // Top 5 Siswa dengan pembayaran terbanyak
    $top_siswa = queryAll("
        SELECT s.nisn, s.nama, COUNT(p.id_pembayaran) as jumlah_pembayaran, SUM(p.jumlah_bayar) as total_bayar 
        FROM siswa s 
        LEFT JOIN pembayaran p ON s.nisn = p.nisn 
        GROUP BY s.nisn, s.nama 
        ORDER BY total_bayar DESC 
        LIMIT 5
    ");
    $stats['top_siswa'] = $top_siswa;

    // Pembayaran per bulan (last 12 months)
    $pembayaran_per_bulan = queryAll("
        SELECT bulan_dibayar, tahun_dibayar, COUNT(*) as jumlah, SUM(jumlah_bayar) as total 
        FROM pembayaran 
        GROUP BY tahun_dibayar, bulan_dibayar 
        ORDER BY tahun_dibayar DESC, MONTH(STR_TO_DATE(bulan_dibayar, '%B')) DESC 
        LIMIT 12
    ");
    $stats['pembayaran_per_bulan'] = $pembayaran_per_bulan;

    ApiResponse::sendResponse(
        ApiResponse::success($stats, 'Dashboard statistics retrieved', 200)
    );
}
