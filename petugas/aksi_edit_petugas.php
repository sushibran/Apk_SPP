<?php 
include "../koneksi.php";
$id_petugas=$_POST['id_petugas'];
$nama_petugas=$_POST['nama_petugas'];    
$username=$_POST['username'];    
$level=$_POST['level'];   
  
$query=mysqli_query($koneksi, "UPDATE petugas SET id_petugas = '$id_petugas', nama_petugas='$nama_petugas',username='$username', level='$level' WHERE id_petugas =$id_petugas");     
 

if(!$query){
    echo "edit gagal cek koding dengan benar";
    } else {
    echo "<script>alert('Data berhasil diubah.');window.location='?url=td_petugas';</script>";
   }
 ?>