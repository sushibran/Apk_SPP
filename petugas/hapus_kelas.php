<?php 
include '../koneksi.php';
    $id=$_GET['id'];     
    $query=mysqli_query($koneksi, "DELETE FROM kelas WHERE id_kelas='$id'");

    echo"<script>alert('Hapus Berhasil');location='?url=td_kelas';</script>";
 ?>