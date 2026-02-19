<?php 
session_start();
include 'koneksi.php';
function aksi_login($username,$password)
{
	include 'koneksi.php';	
	$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE
		nis='$username' AND password='$password'");
	$cek = mysqli_num_rows($query);
	if ($cek>0) {
		$data = mysqli_fetch_array($query);
		$_SESSION['nisn'] = $data['nisn'];

		$jenis="siswa";
		return $jenis;
	} 
	
}

$username = $_POST['txtusername'];
$password = md5($_POST['txtpassword']);

if (aksi_login($username,$password)=="siswa") {
	$_SESSION['nis'] = $username;
	header("location:siswa/");
}else {
	header("location:index.php?pesan=gagal");
}
?>
