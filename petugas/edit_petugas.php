<?php
include "../koneksi.php";
$id_petugas = $_GET['id_petugas'];
$query = mysqli_query($koneksi, "SELECT * FROM petugas WHERE id_petugas=$id_petugas");
$data = mysqli_fetch_array($query);
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Petugas</title>
</head>

<body>
    <h1>Edit Petugas</h1>
    <h5>(Tanpa Password)</h5>
    <hr>
    <form action="?url=aksi_edit_petugas" method="post">
        <div class="form-group mb-2">
            <label>Id Petugas</label>
            <input type="text" name="id_petugas" class="form-control" value="<?php echo $data['id_petugas']; ?>" disabled />
            <input type="text" name="id_petugas" class="form-control" value="<?php echo $data['id_petugas']; ?>" hidden />
        </div>
        <div class="form-group mb-2">
            <label>Nama Petugas</label>
            <input type="text" name="nama_petugas" class="form-control" value="<?php echo $data['nama_petugas']; ?>" required/>
        </div>
        <div class="form-group mb-2">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required/>
        </div>
        <div class="form-group mb-2">
            <label for="">Jabatan</label>
            <select name="level" id="" class="form-control" value="" required>
                <option value="<?php echo $data['level']; ?>"><?php echo $data['level']; ?></option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option></br>
            </select>
        </div>
        <div class="form-group mb-2">
            <input type="submit" value="Edit" class="btn btn-primary">
            <a href="?url=td_petugas" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?');">Kembali</a>
            <a href="?url=edit_petugas2&id_petugas=<?= $data['id_petugas'];?>">Mau Edit beserta Password?Klik sini</a>
        </div>
</body>

</html>