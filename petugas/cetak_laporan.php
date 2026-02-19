<?php
include '../koneksi.php';

if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];

    $sql = "SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND siswa.id_kelas=kelas.id_kelas
AND pembayaran.id_spp=spp.id_spp AND pembayaran.id_petugas=petugas.id_petugas AND pembayaran.nisn='$nisn' ORDER BY tgl_bayar DESC";

} elseif (isset($_GET['from_date']) && isset($_GET['to_date'])) {
    $from_date = $_GET["from_date"];
    $to_date = $_GET["to_date"];

    $sql = "SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND siswa.id_kelas=kelas.id_kelas
    AND pembayaran.id_spp=spp.id_spp AND pembayaran.id_petugas=petugas.id_petugas AND tgl_bayar BETWEEN '$from_date' AND '$to_date' ORDER BY tgl_bayar DESC";
} else {
    $sql = "SELECT * FROM pembayaran,siswa,kelas,spp,petugas WHERE pembayaran.nisn=siswa.nisn AND siswa.id_kelas=kelas.id_kelas
    AND pembayaran.id_spp=spp.id_spp AND pembayaran.id_petugas=petugas.id_petugas ORDER BY tgl_bayar DESC";
}
?>

<head>
    <title>Laporan Pembayaran SPP</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/dashboard/">
    <link rel="stylesheet" href="../style/css/bootstrap.min.css">
    <link href="../style/css/dashboard.css" rel="stylesheet">
</head>


<body>
    <div class="container mt-5">
        <h3>Laporan Pembayaran SPP</h3>

        <hr>
        <table border="2" class="table table-striped table-bordered">
            <tr class="fw-bold">
                <th>No</th>
                <th>NIS</th>
                <th>NISN</th>
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
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, $sql);
            foreach ($query as $data) {
            ?>
                <tr>
                    <td> <?= $no++; ?> </td>
                    <td> <?= $data['nis'] ?> </td>
                    <td> <?= $data['nisn'] ?> </td>
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

        </table>
    </div>
    <a href="index.php?url=laporan">
    </a>
</body>
<script src="../style/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
<script src="../style/js/dashboard.js"></script>
<script src="../style/js/dashboard.js"></script>
<script type="text/javascript">
    window.print();
</script>


</html>