<?php
if (!isset($conn)) {
	include '../config.php';
}
$result = FetchSpecific(
  "jadwal_pelajaran 
   JOIN kelas ON jadwal_pelajaran.kelas_id = kelas.id
   LEFT JOIN users ON jadwal_pelajaran.guru_id = users.id",
  "1=1",
  "jadwal_pelajaran.id,
   jadwal_pelajaran.hari,
   jadwal_pelajaran.jam_mulai,
   jadwal_pelajaran.jam_selesai,
   jadwal_pelajaran.mata_pelajaran,
   jadwal_pelajaran.kelas_id,
   jadwal_pelajaran.guru_id,
   kelas.nama_kelas AS kelas,
   users.username AS guru",
   false
);
?>
<h1>Welcome</h1>
<hr>
<div class="row">
	<div class="col">
		<h2>Jadwal Pelajaran Hari ini</h2>
		<table class="table table-bordered table-striped mb-0">
		  <thead class="table-dark">
			<tr>
			  <th>No</th>
			  <th>Hari</th>
			  <th>Kelas</th>
			  <th>Mata Pelajaran</th>
			  <th>Jam Mulai</th>
			  <th>Jam Selesai</th>
			</tr>
		  </thead>
		  <tbody>
			<?php 
			$no = 1;
			foreach($result as $row) { ?>
			<tr>
			  <td><?= $no++ ?></td>
			  <td><?= htmlspecialchars($row['hari']) ?></td>
			  <td><?= htmlspecialchars($row['kelas']) ?></td>
			  <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
			  <td><?= htmlspecialchars($row['jam_mulai']) ?></td>
			  <td><?= htmlspecialchars($row['jam_selesai']) ?></td>
			</tr>
			<?php } ?>
		  </tbody>
		</table>
	</div>
	<div class="col">
		<h2>Jadwal Pelajaran Hari ini</h2>
	</div>
</div>