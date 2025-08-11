<?php
include '../config.php';

if (isset($_POST['submit']) && isUserLoggedIn()) {
	redirectTo("pelajaran");
	exit;
}
?>