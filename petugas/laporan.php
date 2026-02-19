<!DOCTYPE html>
<html lang="en">

<body>

    <h3>Laporan Pembayaran SPP</h3>
    <div class="row">
        <form method="post">
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group ">
                        <label>Filter Dari :</label>
                        <input type="date" class="form-control" name="from_date" placeholder="Dari" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Sampai :</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="to_date" placeholder="Sampai" required>
                        <button class="btn btn-primary" type="submit" name="filter_bulan"><span data-feather="search"></span> Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    require 'functions_td_laporan.php';
    $no = 1;
    $laporan = query("SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND siswa.id_kelas=kelas.id_kelas
            AND pembayaran.id_spp=spp.id_spp AND pembayaran.id_petugas=petugas.id_petugas ORDER BY tgl_bayar DESC");

    if (isset($_POST["filter_bulan"])) {

        $from_date = $_POST["from_date"];
        $to_date = $_POST["to_date"];

        $laporan = sortirperbulan($from_date, $to_date);
    }
?>
<?php
require_once 'functions_cetak_laporan.php';
if (isset($_POST["filter_bulan"])) { 
    $print_url = "cetak_laporan.php?from_date=$from_date&to_date=$to_date";
    no_resub();
}else { 
    $print_url = "cetak_laporan.php";
}
?>
<a href="<?= $print_url ?>" class="btn btn-success mt-3"><span data-feather="printer"></span> Cetak Laporan</a>
<hr>
    <table id="tblaporan" class="table table-striped table-bordered">
        <thead>
        <tr class="fw-bold">
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Tahun SPP</th>
            <th>Nominal Dibayar</th>
            <th>Sudah Dibayar</th>
            <th>Tanggal Bayar</th>
            <th>Bulan Dibayar</th>
            <th>Tahun Dibayar</th>
            <th>Petugas</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($laporan as $data) {
        ?>
            <tr>
                <td> <?= $no++; ?> </td>
                <td> <?= $data['nis'] ?> </td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['nama_kelas'] ?></td>
                <td><?= $data['tahun'] ?></td>
                <td><?= number_format($data['nominal'], 2, ',', '.'); ?></td>
                <td><?= number_format($data['jumlah_bayar'], 2, ',', '.'); ?></td>
                <td><?= $data['tgl_bayar'] ?></td>
                <td><?= $data['bulan_dibayar'] ?></td>
                <td><?= $data['tahun_dibayar'] ?></td>
                <td><?= $data['nama_petugas'] ?></td>
            </tr>
        <?php }
        ?>
        </tbody>
    </table>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
"></script>
<script src="../style/js/just_paginate.js"></script>
</html>