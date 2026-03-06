<?php

$koneksi = mysqli_connect('localhost', 'root', '', 'db_spp');

// Check connection
if (!$koneksi) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($koneksi, "utf8");

?>
