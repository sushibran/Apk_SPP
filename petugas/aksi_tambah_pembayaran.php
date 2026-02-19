<?php
include "../koneksi.php";
$id_petugas = $_SESSION['id_petugas'];
$nisn = $_POST['nisn'];
$tgl_bayar = $_POST['tgl_bayar'];
$bulan_dibayar = $_POST['bulan_dibayar'];
$tahun_dibayar = $_POST['tahun_dibayar'];
$id_spp = $_POST['id_spp'];
$jumlah_bayar = $_POST['jumlah_bayar'];

$query = mysqli_query($koneksi, "INSERT INTO pembayaran(id_petugas,nisn,tgl_bayar,bulan_dibayar,tahun_dibayar,id_spp,jumlah_bayar) 
VALUES('$id_petugas','$nisn','$tgl_bayar','$bulan_dibayar','$tahun_dibayar','$id_spp','$jumlah_bayar')");

if ($query) {
  echo "<script>alert('Pembayaran Berhasil.');window.location='?url=td_pembayaran';</script>";
} else {
  die(mysqli_error($koneksi));
}
?>