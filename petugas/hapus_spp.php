<?php 
include '../koneksi.php';
    $id=$_GET['id'];     
    $query=mysqli_query($koneksi, "DELETE FROM spp WHERE id_spp='$id'");

    echo"<script>alert('Hapus Berhasil');location='?url=td_spp';</script>";
 ?>