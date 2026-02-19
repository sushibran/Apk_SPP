<?php
include "../koneksi.php";
$id_kelas = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas=$id_kelas");
$data = mysqli_fetch_array($query);
?>



<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Kelas</title>
</head>

<body>
	<h1>Edit Kelas</h1>
	<form action="?url=aksi_edit_kelas" method="post">
		<div class="form-group mb-2">
			<input name="id" value="<?= $data['id_kelas']; ?>" class="form-control" disabled />
			<input name="id" value="<?= $data['id_kelas']; ?>" class="form-control" hidden />
		</div>
		<div class="form-group mb-2">
			<label>Nama Kelas</label>
			<input type="text" name="nama_kelas" value="<?= $data['nama_kelas']; ?>" class="form-control" required />
		</div>
		<div class="form-group mb-2">
			<label>Kompetensi Keahlian</label>
			<input type="text" name="kompetensi_keahlian" value="<?= $data['kompetensi_keahlian']; ?>" class="form-control" required/>
		</div>
		<input type="submit" value="Edit" class="btn btn-primary">
		<a href="?url=td_kelas"><input type="button" value="kembali" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?');"></a>
</body>

</html>