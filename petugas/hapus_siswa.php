<?php 
include '../koneksi.php';
    $nisn=$_GET['nisn'];     
    $query=mysqli_query($koneksi, "DELETE FROM siswa WHERE nisn='$nisn'");

    if (!$query) {
        echo"Gagal coy";
        die(mysqli_error($koneksi));
    }else{
        echo"<script>alert('Hapus Berhasil');location='?url=td_siswa';</script>";
    }
 ?>