
<?php 
include "../koneksi.php";
$id_spp = $_GET['id'];
$query= mysqli_query($koneksi,"SELECT * FROM spp WHERE id_spp=$id_spp");
$data = mysqli_fetch_array($query);
 ?>



 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Edit SPP</title>
 </head>
 <body>
 <h1>Edit SPP</h1>
<form action="?url=aksi_edit_spp" method="post"> 
<div class="form-group mb-2">
<input name="id" value="<?php echo $data['id_spp']; ?>" class="form-control"  disabled/>
<input name="id" value="<?php echo $data['id_spp']; ?>" class="form-control"  hidden/>
</div>
<div class="form-group mb-2">
<label>Tahun</label><input type="text" name="tahun"  maxlength="4" class="form-control" value="<?php echo $data['tahun']; ?>" readonly/>
</div>
<div class="form-group mb-2">
<label>Nominal</label><input type="text" name="nominal"  maxlength="14" class="form-control" value="<?php echo $data['nominal']; ?>" required=""/>
</div>
<div class="form-group mb-2">
<input type="submit" value="Edit" class="btn btn-primary">
<a href="?url=td_spp"><input type="button" class="btn btn-danger" value="Kembali" onclick="return confirm('Anda yakin akan keluar?');"></a>
</div>
 </body>
 </html>