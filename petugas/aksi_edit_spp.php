<?php
include "../koneksi.php";
$id_spp = $_POST['id'];
$tahun = $_POST['tahun'];
$nominal = $_POST['nominal'];
$query = mysqli_query($koneksi, "UPDATE spp SET id_spp = '$id_spp', tahun='$tahun', nominal='$nominal' WHERE id_spp =$id_spp");

if ($query) {
  echo "<script>alert('Data berhasil diubah.');window.location='?url=td_spp';</script>";
} else {
  die(mysqli_error($koneksi));
}
