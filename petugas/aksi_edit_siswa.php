<?php 
$nisn=$_GET['nisn'];
$nis=$_POST['nis'];
$nama=$_POST['nama'];
$id_kelas=$_POST['id_kelas'];
$id_spp=$_POST['id_spp'];
$alamat=$_POST['alamat'];
$no_telp=$_POST['no_telp'];    


include "../koneksi.php";
$query=mysqli_query($koneksi, "UPDATE siswa SET nisn ='$nisn', nis='$nis',nama='$nama', id_kelas='$id_kelas',
id_spp='$id_spp',alamat='$alamat',no_telp='$no_telp' WHERE nisn ='$nisn'"); 

 if($query){
     echo "<script>alert('Data berhasil diubah.');window.location='?url=td_siswa';</script>";
    } else {
        echo "edit gagal cek koding dengan benar";
        die(mysqli_error($koneksi));
   }
 ?>