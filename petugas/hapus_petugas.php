<?php 
include '../koneksi.php';
    $id_petugas=$_GET['id_petugas'];     
    $query=mysqli_query($koneksi, "DELETE FROM petugas WHERE id_petugas='$id_petugas'");

    if (!$query) {
        echo"Gagal coy";
        die(mysqli_error($koneksi));
    }else{
        echo"<script>alert('Hapus Berhasil');location='?url=td_petugas';</script>";
    }
 ?>