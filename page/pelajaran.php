<?php
if (!isset($conn)) {
	include '../config.php';
}
$result = mysqli_query($conn, "SELECT * FROM jadwal_pelajaran");
$logged = isUserLoggedIn()
?>
<h2>Data Jadwal Pelajaran</h2>

<?php
if ($logged) {
	?>
	<div class="d-flex justify-content-between mb-3">
	<a href="#" onclick="FurtexUtil.showAnimated(tambah)" class="btn btn-coklat">+ Tambah Jadwal</a>
	</div>
	<?php
}
?>

<div class="container-table">
<table class="table table-bordered table-striped mb-0">
  <thead class="table-dark">
	<tr>
	  <th>No</th>
	  <th>Hari</th>
	  <th>Mata Pelajaran</th>
	  <th>Jam Mulai</th>
	  <th>Jam Selesai</th>
	  <?php
		if ($logged) {
			echo "<th>Aksi</th>";
		}
		?>
	</tr>
  </thead>
  <tbody>
	<?php 
	$no = 1;
	while ($row = mysqli_fetch_assoc($result)) : ?>
	<tr>
	  <td><?= $no++ ?></td>
	  <td><?= htmlspecialchars($row['hari']) ?></td>
	  <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
	  <td><?= htmlspecialchars($row['jam_mulai']) ?></td>
	  <td><?= htmlspecialchars($row['jam_selesai']) ?></td>
	  <?php
		if ($logged) {
			?>
		  <td>
			<a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-coklat">Edit</a>
			<a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
		  </td>
		  <?php
		}
		?>
	</tr>
	<?php endwhile; ?>
  </tbody>
</table>
</div>

<script>
let tambah;
fetch(`/JawaPeleket/act/getRows.php?table=kelas`)
  .then(res => res.json())
  .then(data => {
    const roleOptions = data.map(row => ({
      value: row.id,
      text: row.nama_kelas
    }));

    tambah = FurtexUtil.createPopup(
      "Tambah Jadwal Pelajaran",
      "OK",
      "Cancel",
      [
        ["dropdown", "Kelas", roleOptions, "kelas"],
		["textbox", "Jam Mulai", "time", "jam-mulai"],
		["textbox", "Jam Selesai", "time", "jam-selesai"],
		["textbox", "Mata Pelajaran", "text", "pelajaran"],
      ],
	  "POST",
	  "act/addPelajaran.php"
    );
  });
</script>