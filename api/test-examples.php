<?php
/**
 * SPP API - Complete Example Tests
 * Run these examples to test the API
 */

$base_url = 'http://localhost/Apk_SPP/api';

// Example 1: Login as Siswa
echo "=== LOGIN AS SISWA ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'username' => '10206080',
        'password' => '123456',
        'type' => 'siswa'
    ])
]);
$response = curl_exec($ch);
echo $response . "\n\n";
$login_result = json_decode($response, true);
$siswa_token = $login_result['data']['token'] ?? null;

// Example 2: Get Current User
if ($siswa_token) {
    echo "=== GET CURRENT USER ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/auth/me",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $siswa_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 3: Get Siswa Detail
if ($siswa_token) {
    echo "=== GET SISWA DETAIL ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/siswa/10206080",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $siswa_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 4: Get Pembayaran History (siswa will receive a `meta` summary field)
if ($siswa_token) {
    echo "=== GET PEMBAYARAN HISTORY ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/pembayaran",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $siswa_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 5: Get All Kelas
echo "=== GET ALL KELAS ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/kelas",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);
$response = curl_exec($ch);
echo $response . "\n\n";

// Example 6: Login as Petugas (Admin)
echo "=== LOGIN AS PETUGAS ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'username' => 'sushibran',
        'password' => '123456',
        'type' => 'petugas'
    ])
]);
$response = curl_exec($ch);
echo $response . "\n\n";
$petugas_result = json_decode($response, true);
$petugas_token = $petugas_result['data']['token'] ?? null;

// Example 7: Get Dashboard Statistics
if ($petugas_token) {
    echo "=== GET DASHBOARD ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/dashboard",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $petugas_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 8: Get Siswa Belum Lunas Report
if ($petugas_token) {
    echo "=== SISWA BELUM LUNAS REPORT ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/report?type=siswa-belum-lunas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $petugas_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 9: Get Pembayaran Per Bulan Report
if ($petugas_token) {
    echo "=== PEMBAYARAN PER BULAN REPORT ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/report?type=pembayaran-per-bulan&tahun=" . date('Y'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $petugas_token
        ]
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 10: Create New Pembayaran
if ($petugas_token) {
    echo "=== CREATE NEW PEMBAYARAN ===\n";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$base_url/pembayaran",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $petugas_token
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'nisn' => '10206080',
            'id_spp' => 2,
            'jumlah_bayar' => 250000,
            'bulan_dibayar' => 'Maret',
            'tahun_dibayar' => '2026',
            'tgl_bayar' => '2026-03-04'
        ])
    ]);
    $response = curl_exec($ch);
    echo $response . "\n\n";
}

// Example 11: Export Siswa to CSV
if ($petugas_token) {
    echo "=== EXPORT SISWA CSV ===\n";
    echo "Check: " . $base_url . "/export?action=export-siswa\n\n";
}

// Example 12: Get API Documentation
echo "=== API ROOT DOCUMENTATION ===\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$base_url/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);
$response = curl_exec($ch);
echo $response . "\n\n";

curl_close($ch);

?>
