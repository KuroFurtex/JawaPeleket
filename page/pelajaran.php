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


$logged = isUserLoggedIn();
?>
<h2>Data Jadwal Pelajaran</h2>

<script>
let tambah, edit, remove;

fetch(`/JawaPeleket/act/getRows.php?table=users`)
  .then(res => res.json())
  .then(guruData => {
    fetch(`/JawaPeleket/act/getRows.php?table=kelas`)
      .then(res => res.json())
      .then(kelasData => {
        const roleOptions = kelasData.map(row => ({
          value: row.id,
          text: row.nama_kelas
        }));

        const day = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];

        const guruOptions = [
          { value: "", text: "— None —" }, // use "" instead of null for HTML
          ...guruData.map(row => ({
            value: row.id,
            text: row.username
          }))
        ];

        // ADD POPUP
        tambah = FurtexUtil.createPopup(
          "Tambah Jadwal Pelajaran",
          "OK",
          "Cancel",
          [
            ["dropdown", "Kelas", roleOptions, "kelas"],
            ["dropdown", "Hari", day, "hari"],
            ["textbox", "Jam Mulai", "time", "jam-mulai"],
            ["textbox", "Jam Selesai", "time", "jam-selesai"],
            ["textbox", "Mata Pelajaran", "text", "pelajaran"],
            ["dropdown", "Guru", guruOptions, "guru"],
          ],
          "POST",
          "act/addPelajaran.php"
        );

        // EDIT POPUP
        edit = FurtexUtil.createPopup(
          "Edit Jadwal Pelajaran",
          "Save",
          "Cancel",
          [
            ["dropdown", "Kelas", roleOptions, "kelas"],
            ["dropdown", "Hari", day, "hari"],
            ["textbox", "Jam Mulai", "time", "jam-mulai"],
            ["textbox", "Jam Selesai", "time", "jam-selesai"],
            ["textbox", "Mata Pelajaran", "text", "pelajaran"],
            ["dropdown", "Guru", guruOptions, "guru"],
          ],
          "POST",
          "act/editPelajaran.php"
        );
		
		remove = FurtexUtil.createPopup(
		  "Hapus <NAMA_PELAJARAN>?",
		  "Hapus",
		  "Cancel",
		  [
			["line", "Apakah anda yakin ingin menghapus <NAMA_PELAJARAN>?"],
		  ],
		  "POST",
		  "act/deletePelajaran.php"
		);

        // add hidden ID field for edit and delete
        const idHidden = document.createElement("input");
        idHidden.type = "hidden";
        idHidden.name = "id";
        edit.querySelector("form").appendChild(idHidden);
		
		const removeHidden = document.createElement("input");
		removeHidden.type = "hidden";
		removeHidden.name = "id";
		remove.querySelector("form").appendChild(removeHidden);
      });
  });

function openEditPopup(row) {
  // set hidden id
  edit.querySelector("input[name='id']").value = row.id;

  // fill inputs
  FurtexUtil.editPopup(edit, {
    kelas: row.kelas_id,       // must include kelas_id in your SELECT!
    hari: row.hari,
    "jam-mulai": row.jam_mulai,
    "jam-selesai": row.jam_selesai,
    pelajaran: row.mata_pelajaran,
    guru: row.guru_id || ""    // fallback to "" if null
  });

  FurtexUtil.showAnimated(edit);
}

function openRemovePopup(row) {
  // set hidden id
  remove.querySelector("input[name='id']").value = row.id;

  // update text to show subject name dynamically
  const caption = remove.querySelector("p");
  caption.textContent = `Apakah anda yakin ingin menghapus "${row.mata_pelajaran}"?`;
  const header = remove.querySelector("h3");
  header.textContent = `Hapus "${row.mata_pelajaran}"?`;

  FurtexUtil.showAnimated(remove);
}


</script>

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
	  <th>Kelas</th>
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
	foreach($result as $row) { ?>
	<tr>
	  <td><?= $no++ ?></td>
	  <td><?= htmlspecialchars($row['hari']) ?></td>
	  <td><?= htmlspecialchars($row['kelas']) ?></td>
	  <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
	  <td><?= htmlspecialchars($row['jam_mulai']) ?></td>
	  <td><?= htmlspecialchars($row['jam_selesai']) ?></td>
	  <?php
		if ($logged) {
			?>
		  <td>
			<a href="#"
			   onclick="openEditPopup(<?= htmlspecialchars(json_encode($row)) ?>)"
			   class="btn btn-sm btn-coklat">Edit</a>
			<a href="#"
			   onclick='openRemovePopup(<?= htmlspecialchars(json_encode($row)) ?>)'
			   class="btn btn-sm btn-danger">Hapus</a>
		  </td>
		  <?php
		}
		?>
	</tr>
	<?php } ?>
  </tbody>
</table>
</div>