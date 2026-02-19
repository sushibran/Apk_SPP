<!doctype html>
<html lang="en">

<head>
  <title>Aplikasi Pembayaran SPP</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">
  <link href="style/css/bootstrap.min.css" rel="stylesheet">
  <link href="style/css/sign-in.css" rel="stylesheet">
</head>

<body class="text-center">

  <main class="form-signin w-100 m-auto">
    <form action="aksi_login.php" method="post">
      <img class="mb-2" src="style/gambar/logo_spp.png" alt="" width="120" height="100">
      <h4 class="fw-normal">Aplikasi Pembayaran SPP</h4>
      <?php
      $file = @$_GET['pesan'];
      if ($file) {
        echo
        "<div class='alert alert-danger'>
        <h6>Username atau Password Salah</h6>
      </div>";
      }else{
        echo"
        <h4 class='fw-normal'>Login Siswa</h4>
        <div class='alert alert-info'>
        <h5 class='fw-normal'>Silahkan Log in terlebih dahulu!</h5>
        </div>";
      }
      ?>

      <div class="form-floating">
        <input type="text" class="form-control" name="txtusername" placeholder="Nis" autofocus required>
        <label for="floatingInput">Nis</label>
      </div>
      <div class="form-floating mt-2">
        <input type="password" class="form-control" name="txtpassword" placeholder="Password" required>
        <label for="floatingPassword">Password</label>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      <a href="index_petugas.php">
        <h5 class="mt-2">Login Sebagai Petugas</h5>
      </a>
      <p class="mt-5 mb-3 text-muted">&copy; SMKN Bantarkalong <img class="mb-1" src="style/gambar/logo_smk.png" alt="" width="23" height="23">
    </form>
  </main>



</body>

</html>