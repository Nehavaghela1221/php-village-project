<?php

require 'admin/auth.php';


require 'config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: gallery-manage.php");
    exit;
}

/* Fetch file info */
$stmt = $conn->prepare("SELECT file_name, file_type FROM gallery WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: gallery-manage.php");
    exit;
}

$row = $result->fetch_assoc();

/* File path */
$filePath = ($row['file_type'] === 'image')
    ? "uploads/gallery/images/" . $row['file_name']
    : "uploads/gallery/videos/" . $row['file_name'];

/* Delete file */
if (file_exists($filePath)) {
    unlink($filePath);
}

/* Delete DB record */
$del = $conn->prepare("DELETE FROM gallery WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

/* ✅ IMPORTANT: FLAG MUST MATCH JS */
header("Location: gallery-manage.php?delete_success=1");
exit;
