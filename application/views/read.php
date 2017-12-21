<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<a href="<?php echo base_url("welcome/tambah"); ?>">Tambah</a>
	<table border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>NPM</th>
				<th>NAMA</th>
				<th>AKSI</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($data as $item) {
				?>
				<tr>
					<td><?php echo $item->id; ?></td>		
					<td><?php echo $item->npm; ?></td>		
					<td><?php echo $item->nama; ?></td>		
					<td>
						<a href="<?php echo base_url("welcome/ubah/".$item->id); ?>">Ubah</a>
						<a href="<?php echo base_url("welcome/hapus/".$item->id); ?>">Hapus</a>
					</td>		
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</body>
</html>
