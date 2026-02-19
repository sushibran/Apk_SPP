<?php 
include '../koneksi.php';
    $id_pembayaran=$_GET['id_pembayaran'];     
    $query=mysqli_query($koneksi, "DELETE FROM pembayaran WHERE id_pembayaran='$id_pembayaran'");

    if ($query) {
        echo"<script>alert('Hapus Berhasil');location='?url=td_pembayaran';</script>";
    } else {
       die(mysqli_error($koneksi));
    }
    
 ?>