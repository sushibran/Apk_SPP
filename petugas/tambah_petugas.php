<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Data Petugas</title>
</head>

<body>

    <h4>Halaman Tambah Data Petugas Baru</h4>
    <a href="?url=td_petugas" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?')" ;>Keluar</a>
    <hr>
    <form method="post">
    <div class="form-group mb-2">
        <label>Username</label>
        <input type='text' name='username' class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>Password</label>
        <input type='text' name='password' class="form-control" required>
    </div>
    <div class="form-group mb-2"> 
        <label>Nama Petugas</label>
        <input type='text' name='nama_petugas' class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label>Jabatan</label>
        <select name="level" id="" class="form-control" required>
            <option value="">Pilih Level</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
        </select>
    </div>
    <div class="form-group mb-2">
        <input type="submit" name="simpan" value="Simpan" class="btn btn-success">
        <input type="reset" value="Kosongkan" class="btn btn-warning" onclick="return confirm('Anda yakin akan mengosongkan?')">
    </div>
    </form>
</body>

</html>

<?php
include '../koneksi.php';
if (isset($_POST['simpan'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_petugas = $_POST['nama_petugas'];
    $level = $_POST['level'];

    $sql = "INSERT INTO petugas(username,password,nama_petugas,level) VALUES('$username',md5('$password'),'$nama_petugas','$level')";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        echo "<script>alert('Daftar Data Baru Berhasil Tersimpan')
	document.location='?url=td_petugas'</script>";
    } else {
        die(mysqli_error($koneksi));
        echo "<script>alert('Maaf data tidak tersimpan');</script>";
    }
}
?>