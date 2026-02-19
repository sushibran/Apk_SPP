<?php
include "../koneksi.php";
$nisn = $_GET['nisn'];
$sql = "SELECT * FROM siswa,kelas,spp WHERE siswa.id_kelas = kelas.id_kelas AND siswa.id_spp = spp.id_spp AND nisn='$nisn'";
$query = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
$data = mysqli_fetch_array($query);
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Siswa</title>
</head>

<body>
    <h1>Edit Siswa</h1>
    <hr>
    <form action="?url=aksi_edit_siswa&nisn=<?= $nisn; ?>" method="post">
        <div class="form-group mb-2">
            <label>NISN </label>
            <input type="text" name="nisn" value="<?php echo $data['nisn']; ?>" hidden />
            <input type="text" name="nisn" class="form-control" value="<?php echo $data['nisn']; ?>" disabled/>
        </div>
        <div class="form-group mb-2">
            <label>NIS </label>
            <input type="text" name="nis" value="<?php echo $data['nis']; ?>" class="form-control" required />
        </div>
        <div class="form-group mb-2">
            <label>Nama Siswa </label>
            <input type="text" name="nama" value="<?php echo $data['nama']; ?>" class="form-control" required />
        </div>
        <div class="form-group mb-2">
            <label>Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option value="<?= $data['id_kelas'] ?>"><?= $data['nama_kelas'] ?> </option>
                <?php
                include "../koneksi.php";
                $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC") or die(mysqli_error($koneksi));
                foreach ($kelas as $data_kelas) { ?>
                    <option value="<?= $data_kelas['id_kelas'] ?>"> <?= $data_kelas['nama_kelas']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label>ID SPP</label>
            <select name="id_spp" id="" class="form-control" required>
                <option value="<?= $data['id_spp']; ?>"> <?= $data['tahun']; ?> - <?= number_format($data['nominal'], 2, ',', '.'); ?> </option>
                <?php
                include '../koneksi.php';
                $spp = mysqli_query($koneksi, "SELECT * FROM spp ORDER BY id_spp ASC ");
                foreach ($spp as $data_spp) {
                ?>
                    <option value="<?= $data_spp['id_spp'] ?>"><?= $data_spp['tahun']; ?> |
                        <?= number_format($data_spp['nominal'], 2, ',', '.'); ?> </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label>Alamat</label>
            <input type="text" name="alamat" value="<?php echo $data['alamat']; ?>" class="form-control" required />
        </div>
        <div class="form-group mb-2">
            <label>No Telpon</label>
            <input type="text" name="no_telp" value="<?php echo $data['no_telp']; ?>" class="form-control" required />
        </div>
        <div class="form-group mb-2">
            <input type="submit" value="Edit" class="btn btn-primary">
            <a href="?url=td_siswa"><input type="button" class="btn btn-danger" value="Kembali" onclick="return confirm('Anda yakin akan keluar?');"></a>
            <a href="?url=edit_siswa2&nisn=<?= $data['nisn'];?>">Mau Edit dengan Password?Klik sini</a>
        </div>
</body>

</html>