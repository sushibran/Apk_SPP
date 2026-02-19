<?php 
include "../koneksi.php";
$id_kelas=$_POST['id'];
$nama_kelas=$_POST['nama_kelas'];    
$kompetensi_keahlian=$_POST['kompetensi_keahlian'];     
$query=mysqli_query($koneksi, "UPDATE kelas SET id_kelas = '$id_kelas', nama_kelas='$nama_kelas', kompetensi_keahlian='$kompetensi_keahlian' WHERE id_kelas =$id_kelas");     
 

if(!$query){
    echo "edit gagal cek koding dengan benar";
    } else {
    echo "<script>alert('Data berhasil diubah.');window.location='?url=td_kelas';</script>";
   }
 ?>