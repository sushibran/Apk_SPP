<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tambah Data SPP</title>
</head>

<body>
	<h1>Data SPP Baru</h1>
	<a href="?url=td_spp" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?')" ;>Keluar</a>
	<hr>
	<form method="post">
		<div class="form-group mb-2">
			<label>Tahun</label>
			<input type="number" name="tahun" maxlength="4" class="form-control" required>
		</div>
		<div class="form-group mb-2">
			<label>Nominal</label>
			<input type="number" name="nominal" maxlength="4" class="form-control" required>
		</div>
		<div class="form-group mb-2">
			<input type="submit" name="simpan" value="Simpan" class="btn btn-success">
			<input type="reset" value="Kosongkan" class="btn btn-warning" onclick="return confirm('Anda yakin akan mengosongkan?')">
		</div>
	</form>

	<?php
	include '../koneksi.php';
	if (isset($_POST['simpan'])) {

	$tahun = $_POST['tahun'];
	$nominal = $_POST['nominal'];


	$query = mysqli_query($koneksi, "insert into spp set 
	tahun = '$tahun',
	nominal = '$nominal'");

		if ($query) {
			echo "<script>alert('Daftar Data Baru Berhasil Tersimpan')
	document.location='?url=td_spp'</script>";
		} else {
			echo "Data Gagal Ditambahkan";
		}
	}
	?>

</body>

</html>