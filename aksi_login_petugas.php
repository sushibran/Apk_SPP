<?php 
session_start();
include 'koneksi.php';
function aksi_login($username,$password)
{
	include './koneksi.php';
	$query = mysqli_query($koneksi,"SELECT * FROM petugas WHERE
		username='$username' AND password='$password'");
	$cek = mysqli_num_rows($query);
	if ($cek>0) {
		$data = mysqli_fetch_array($query);
		$_SESSION['level'] = $data['level'];
		$_SESSION['nama_petugas'] = $data['nama_petugas'];
		$_SESSION['id_petugas'] = $data['id_petugas'];

		$jenis="petugas";
		return $jenis;
	}	
	
}

$username = $_POST['txtusername'];
$password = md5($_POST['txtpassword']);

if (aksi_login($username,$password)=="petugas") {
	$_SESSION['username'] = $username;
	header("location:petugas/");
} else {
	header("location:index_petugas.php?pesan=gagal");
}
?>
