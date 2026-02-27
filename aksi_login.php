<?php 
session_start();
include 'koneksi.php';
function aksi_login($username,$password)
{
	include 'koneksi.php';	
	// sanitize input to prevent SQL injection and allow login by nisn or nis
	$username = mysqli_real_escape_string($koneksi, $username);
	$password = mysqli_real_escape_string($koneksi, $password);
	$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE
		(nisn='$username' OR nis='$username') AND password='$password'");
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
	header("location:siswa/");
}else {
	header("location:index.php?pesan=gagal");
}
?>
