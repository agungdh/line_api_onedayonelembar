<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="post" action="<?php echo base_url('welcome/aksi_tambah'); ?>">
		NPM :
		<input type="text" name="npm">
		<br>
		Nama :
		<input type="text" name="nama">
		<br>
		<a href="<?php echo base_url(); ?>">Kembali</a>
		<input type="submit" name="submit" value="Kirim">
	</form>
</body>
</html>
