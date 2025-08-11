<?php
include "../config.php";
$table = $_GET['table'] ?? '';
if (!$table) {
    echo json_encode(["error" => "No table specified"]);
    exit;
}

$data = fetchAll($table);

header('Content-Type: application/json');
echo json_encode($data);
?>
