<?php
require '../admin/auth.php';   // 🔐 common admin protection
require '../config/db.php';

$id = $_GET['id'] ?? '';

if (!is_numeric($id)) {
    header("Location: announcement-manage.php?error=Invalid request");
    exit;
}

$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: announcement-manage.php?deleted=1");
} else {
    header("Location: announcement-manage.php?error=Delete failed");
}

$stmt->close();
exit;
