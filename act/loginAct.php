<?php
include '../config.php';

session_start();

echo implode(", ", $_POST);
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    $user = userCheck($nama, $pass);
	
	if (is_string($user)) {
		$_SESSION['pesan'] = $user;
	} else {
		$_SESSION['pesan'] = "Logged In";
		loginUser($nama);
	}
	redirectTo("home");
	exit;
}
?>