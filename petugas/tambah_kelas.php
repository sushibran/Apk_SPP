<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tambah Data Kelas</title>
</head>

<body>
	<h1>Data Kelas Baru</h1>
	<a href="?url=td_kelas"><input type="button" value="kembali" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?');"></a>
	<hr>
	<form method="post">
		<div class="form-group mb-2">
			<label>Nama Kelas</label>
			<input type="text" name="nama_kelas" class="form-control" required>
		</div>
		<div class="form-group mb-2">
			<label>Kompetensi Keahlian</label>
			<input type="text" name="kompetensi_keahlian" class="form-control" required>
		</div>
		<div class="form-group mb-2">
			<input type="submit" name="simpan" value="Simpan" class="btn btn-success">
			<input type="reset" value="Kosongkan" class="btn btn-warning" onclick="return confirm('Anda yakin akan mengosongkan?')">
		</div>
	</form>
	<?php
	include '../koneksi.php';
	if (isset($_POST['simpan'])) {
		
		$nama_kelas = $_POST['nama_kelas'];
		$kompetensi_keahlian = $_POST['kompetensi_keahlian'];


		$query = mysqli_query($koneksi, "insert into kelas set 
			nama_kelas = '$nama_kelas',
			kompetensi_keahlian = '$kompetensi_keahlian'");

		if ($query) {
			echo "<script>alert('Daftar Data Baru Berhasil Tersimpan')
	document.location='?url=td_kelas'</script>";
		} else {
			echo "Data Gagal Ditambahkan";
		}
	}
	?>

</body>

</html>