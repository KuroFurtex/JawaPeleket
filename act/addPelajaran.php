<?php
include '../config.php';

if (isset($_POST['submit']) && isUserLoggedIn()) {
	$kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
	$hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam-mulai']);
    $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam-selesai']);
    $pelajaran = mysqli_real_escape_string($conn, $_POST['pelajaran']);
    $guru = isset($_POST['guru']) && $_POST['guru'] !== "" ? mysqli_real_escape_string($conn, $_POST['guru']) : null;
	$values = [$kelas, $hari, $jam_mulai, $jam_selesai, $pelajaran, $guru];
	echo implode(", ", addToTable(
		["kelas_id", "hari", "jam_mulai", "jam_selesai", "mata_pelajaran", "guru_id"],
		"jadwal_pelajaran",
		$values,
	));
	redirectTo("pelajaran");
	exit;
}
?>