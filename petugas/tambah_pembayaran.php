<?php
include "../koneksi.php";
$nisn = $_GET['nisn'];
$kekurangan = $_GET['kekurangan'];
$sql = "SELECT * FROM siswa,spp,kelas WHERE siswa.id_kelas = kelas.id_kelas AND siswa.id_spp = spp.id_spp AND nisn = '$nisn'";
$query = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
$data = mysqli_fetch_array($query);
?>


<h4>Halaman Pembayaran SPP</h4>
<div class="text-end">
<a href="?url=td_pembayaran " value="kembali" class="btn btn-danger" onclick="return confirm('Anda yakin akan keluar?');">Kembali</a>
</div>
<hr>
<form action="?url=aksi_tambah_pembayaran&$nisn=<?= $nisn; ?>" method="post">
    <input type="hidden" name="id_spp" value="<?= $data['id_spp']; ?>" required />
    <div class="form-group mb-2">
        <label>Nama Petugas</label>
        <input type="text" value="<?= $_SESSION['nama_petugas']; ?>" disabled class="form-control" required />
    </div>
    <div class="form-group mb-2">
        <label>NISN</label>
        <input type="text" name="nisn" value="<?= $data['nisn']; ?>" hidden class="form-control" required />
        <input type="text" value="<?= $data['nisn']; ?>" disabled class="form-control" required />
    </div>
    <div class="form-group mb-2">
        <label>Nama Siswa</label>
        <input type="text" disabled value="<?= $data['nama']; ?>" class="form-control" required />
    </div>
    <div class="form-group mb-2">
        <label>Tanggal Bayar</label>
        <input value="<?= date('Y-m-d')?>" name="tgl_bayar" class="form-control" readonly required>
    </div>
    <div class="form-group mb-2">
        <label>Bulan Dibayar</label>
        <select name="bulan_dibayar" class="form-control" required>
            <option value="">Pilih Bulan Dibayar</option>
            <option value="Januari">Januari</option>
            <option value="Februari">Februari</option>
            <option value="Maret">Maret</option>
            <option value="April">April</option>
            <option value="Mei">Mei</option>
            <option value="Juni">Juni</option>
            <option value="Juli">Juli</option>
            <option value="Agustus">Agustus</option>
            <option value="September">September</option>
            <option value="Oktober">Oktober</option>
            <option value="November">November</option>
            <option value="Desember">Desember</option>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Tahun Dibayar</label>
        <select name="tahun_dibayar" class="form-control" required>
            <option value="">Pilih Tahun Bayar</option>
            <?php
            for ($i = 2020; $i <= date('Y'); $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Jumlah Bayar (Jumlah yang harus dibayar adalah <b><?= number_format($kekurangan, 2, ',', '.') ?></b>)</label>
        <input type="number" name="jumlah_bayar" max="<?= $kekurangan; ?>" class="form-control" required>
    </div>
    <input type="submit" value="Simpan" class="btn btn-primary">
    <input type="reset" value="Kosongkan" class="btn btn-warning" onclick="return confirm('Anda yakin akan mengosongkan?')">
</form>