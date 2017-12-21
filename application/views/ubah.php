<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="post" action="<?php echo base_url('welcome/aksi_ubah'); ?>">
		<input type="hidden" name="id" value="<?php echo $data->id; ?>">
		NPM :
		<input type="text" name="npm" value="<?php echo $data->npm; ?>">
		<br>
		Nama :
		<input type="text" name="nama" value="<?php echo $data->nama; ?>">
		<br>
		<a href="<?php echo base_url(); ?>">Kembali</a>
		<input type="submit" name="submit" value="Kirim">
	</form>
</body>
</html>
