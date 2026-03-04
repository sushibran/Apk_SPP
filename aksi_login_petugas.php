<?php 
session_start();
include 'koneksi.php';

function aksi_login($username, $password)
{
	include './koneksi.php';
	$username = mysqli_real_escape_string($koneksi, $username);
	$query = mysqli_query($koneksi, "SELECT * FROM petugas WHERE username='$username' AND password='$password'");
	$cek = mysqli_num_rows($query);
	if ($cek > 0) {
		$data = mysqli_fetch_array($query);
		$_SESSION['level'] = $data['level'];
		$_SESSION['nama_petugas'] = $data['nama_petugas'];
		$_SESSION['id_petugas'] = $data['id_petugas'];
		$_SESSION['username'] = $username;
		$jenis = "petugas";
		return $jenis;
	}	
	return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = isset($_POST['txtusername']) ? $_POST['txtusername'] : '';
	$password = isset($_POST['txtpassword']) ? md5($_POST['txtpassword']) : '';

	if (aksi_login($username, $password) == "petugas") {
		header("location:petugas/");
	} else {
		header("location:index_petugas.php?pesan=gagal");
	}
} else {
	header("location:index_petugas.php?pesan=gagal");
}
?>
