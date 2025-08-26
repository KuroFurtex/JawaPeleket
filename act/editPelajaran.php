<?php
include '../config.php';

$guru_id = !empty($_POST['guru']) ? $_POST['guru'] : NULL;

$stmt = $conn->prepare("UPDATE jadwal_pelajaran
   SET kelas_id=?, hari=?, jam_mulai=?, jam_selesai=?, mata_pelajaran=?, guru_id=?
   WHERE id=?");
$stmt->bind_param("issssii", $_POST['kelas'], $_POST['hari'], $_POST['jam-mulai'],
                  $_POST['jam-selesai'], $_POST['pelajaran'], $guru_id, $_POST['id']);
$stmt->execute();
redirectTo("pelajaran");
exit;
?>