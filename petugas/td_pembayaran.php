<h2>Halaman Pilih Data Siswa Untuk Pembayaran</h2>
<hr>
<table id="table" class="table table-striped table-bordered">
    <thead>
        <tr class="fw-bold">
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tahun SPP</th>
            <th>Nominal</th>
            <th>Sudah Dibayar</th>
            <th>Kekurangan</th>
            <th>Status</th>
            <th>History</th>
            <th>Print</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $sql = "SELECT * FROM siswa,kelas,spp WHERE siswa.id_kelas = kelas.id_kelas AND siswa.id_spp = spp.id_spp ORDER BY nisn ASC";
        $query = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
        foreach ($query as $data) {

            $data_pembayaran = mysqli_query($koneksi, "SELECT SUM(jumlah_bayar) as jumlah_bayar 
                FROM pembayaran WHERE nisn='$data[nisn]'");

            $data_pembayaran = mysqli_fetch_array($data_pembayaran);

            $sudah_bayar = $data_pembayaran['jumlah_bayar'];

            $kekurangan = $data['nominal'] - $data_pembayaran['jumlah_bayar'];
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $data['nisn'] ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= $data['nama_kelas'] ?></td>
                <td><?= $data['tahun'] ?></td>
                <td><?= number_format($data['nominal'], 2, ',', '.'); ?></td>
                <td><?= number_format($sudah_bayar, 2, ',', '.'); ?></td>
                <td><?= number_format($kekurangan, 2, ',', '.'); ?></td>
                <td>
                    <?php
                    if ($kekurangan == 0) {
                        echo "<span class='btn btn-success'><span data-feather='check-square'></span> Sudah Lunas</span>";
                    } else { ?>
                        <a href="?url=tambah_pembayaran&nisn=<?= $data['nisn'] ?>&kekurangan=<?= $kekurangan ?>" class="btn btn-danger"><span data-feather="file-plus"></span> Pilih & Bayar</a>

                    <?php } ?>
                </td>
                <td>
                    <a href="?url=td_pembayaran&nisn=<?= $data['nisn'] ?>" class="btn btn-info "><span data-feather="archive"></span> History</a>
                </td>
                <td>
                    <a href="cetak_laporan.php?nisn=<?= $data['nisn'] ?>" class="btn btn-info "><span data-feather="printer"></span> Cetak History</a>
                </td>

            </tr>
        <?php
        }
        ?>
</table>
</tbody>
<script src="https://code.jquery.com/jquery-3.5.1.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js
"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js
"></script>
<script src="../style/js/paginate.js"></script>
<?php
if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];
?>
        <div class="text-end">
            History Pembayaran
        <a href="?url=td_pembayaran"><span data-feather="chevrons-up" class="align-text-bottom"></span></a>
        </div>
    <hr>
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="fw-bold">
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tahun SPP</th>
                <th>Nominal Dibayar</th>
                <th>Sudah Dibayar</th>
                <th>Tanggal Bayar</th>
                <th>Petugas</th>
                <th>Hapus</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $sql = "SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND 
        siswa.id_kelas = kelas.id_kelas AND pembayaran.id_spp=spp.id_spp
        AND pembayaran.id_petugas=petugas.id_petugas AND pembayaran.nisn='$nisn' ORDER BY tgl_bayar ASC";
            $query = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
            foreach ($query as $data) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['nisn'] ?></td>
                    <td><?= $data['nama'] ?></td>
                    <td><?= $data['nama_kelas'] ?></td>
                    <td><?= $data['tahun'] ?></td>
                    <td><?= number_format($data['nominal'], 2, ',', '.'); ?></td>
                    <td><?= number_format($data['jumlah_bayar'], 2, ',', '.'); ?></td>
                    <td><?= $data['tgl_bayar'] ?></td>
                    <td><?= $data['nama_petugas'] ?></td>
                    <td>
                        <a href="?url=hapus_pembayaran&id_pembayaran=<?= $data['id_pembayaran'] ?>" class="btn btn-danger"><span data-feather="trash-2"></span> Hapus</a>
                    </td>
                </tr>
            <?php
            }
            ?>
    </table>
    </tbody>
<?php
}
?>