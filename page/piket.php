<?php
if (!isset($conn)) {
	include '../config.php';
}
$piket = mysqli_query($conn, "SELECT pk.id, pk.hari, u.nama_lengkap, k.nama_kelas 
    FROM piket_kelas pk 
    JOIN users u ON pk.siswa_id = u.id 
    JOIN kelas k ON pk.kelas_id = k.id");
?>
<h2>Data Jadwal Piket</h2>


<?php
if (isUserLoggedIn()) {
	?>
	<div class="d-flex justify-content-between mb-3">
	<a href="tambah.php" class="btn btn-coklat">+ Tambah Piket</a>
	</div>
	<?php
}
?>

<div class="container-table">
<table class="table table-bordered table-striped">
  <thead class="table-dark">
	<tr>
	  <th>No</th>
	  <th>Hari</th>
	  <th>Nama Siswa</th>
	  <th>Kelas</th>
	  <th>Aksi</th>
	</tr>
  </thead>
  <tbody>
	<?php 
	$no = 1;
	while ($row = mysqli_fetch_assoc($piket)) : ?>
	<tr>
	  <td><?= $no++ ?></td>
	  <td><?= htmlspecialchars($row['hari']) ?></td>
	  <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
	  <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
	  <td>
		<a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-coklat">Edit</a>
		<a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
	  </td>
	</tr>
	<?php endwhile; ?>
  </tbody>
</table>
</div>
