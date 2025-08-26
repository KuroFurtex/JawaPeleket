<?php
if (!isset($conn)) {
    include '../config.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM jadwal_pelajaran WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        redirectTo("pelajaran"); // redirect back to list
        exit;
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
