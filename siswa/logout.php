<?php  
session_start();

// Hanya proses logout jika request adalah POST dan ada parameter logout_confirm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout_confirm']) && $_POST['logout_confirm'] === 'confirmed') {
    session_destroy();
    header("location:../index.php");
    exit;
} else {
    // Jika akses langsung dari URL atau tidak ada konfirmasi, redirect ke halaman siswa
    header("location:index.php");
    exit;
}
?>