<head>
    <title>Tambah Data Siswa</title>
</head>

<body>
    <h3>Data Siswa Baru</h3>
    <a href="?url=td_siswa"  class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?');">Kembali</a>
    <hr>
    <form method="post">

        <div class="form-group mb-2">
            <label>NISN</label>
            <input type="number" name="nisn" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>NIS</label>
            <input type="number" name="nis" maxlength="8" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Nama Siswa</label>
            <input type="text" name="nama_siswa" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option>Pilih Kelas</option>
                <?php
                include "../koneksi.php";
                $query = mysqli_query($koneksi, "SELECT * FROM kelas") or die(mysqli_error($koneksi));
                while ($data = mysqli_fetch_array($query)) {
                    echo "<option value=$data[id_kelas]>$data[nama_kelas]</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label>ID SPP</label>
            <select name="id_spp" id="" class="form-control" required>
                <option value="">Pilih SPP</option>
                <?php
                include '../koneksi.php';
                $spp = mysqli_query($koneksi, "SELECT * FROM spp ORDER BY id_spp ASC ");
                foreach ($spp as $data_spp) {
                ?>
                    <option value="<?= $data_spp['id_spp'] ?>"><?= $data_spp['tahun']; ?> | <?= number_format($data_spp['nominal'], 2, ',', '.'); ?> </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>No Telpon</label>
            <input type="text" name="no_telp" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <input type="submit" name="simpan" value="Simpan" class="btn btn-success">
            <input type="reset" value="Kosongkan" class="btn btn-warning" onclick="return confirm('Anda yakin akan mengosongkan?')">
        </div>
    </form>
</body>
<?php
include '../koneksi.php';
if (isset($_POST['simpan'])) {

    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $id_kelas = $_POST['id_kelas'];
    $id_spp = $_POST['id_spp'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];

    $query = mysqli_query($koneksi, "insert into siswa set 
			nisn = '$nisn',
			nis = '$nis',
			nama = '$nama_siswa',
			id_kelas = '$id_kelas',
			id_spp = '$id_spp',
			password = md5('$password'),
			alamat = '$alamat',
			no_telp = '$no_telp'");


    if (!$query) {
        echo "Data Gagal Ditambahkan";
        die(mysqli_error($koneksi));
    } else {
        echo "<script>alert('Daftar Data Baru Berhasil Tersimpan')
	document.location='?url=td_siswa'</script>";
    }
}
