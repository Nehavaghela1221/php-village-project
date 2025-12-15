<?php
require '../admin/auth.php';   // 🔐 common admin protection
require '../config/db.php';

// Get & clean input
$id          = $_POST['id'] ?? '';
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$expire_at   = $_POST['expire_at'] ?? '';

// Validate
if (!is_numeric($id) || $title === '' || $description === '' || $expire_at === '') {
    header("Location: announcement-manage.php?error=Invalid input");
    exit;
}

// Update safely
$stmt = $conn->prepare(
  "UPDATE announcements
   SET title = ?, description = ?, expire_at = ?
   WHERE id = ?"
);

$stmt->bind_param("sssi", $title, $description, $expire_at, $id);

if ($stmt->execute()) {
    header("Location: announcement-manage.php?updated=1");
} else {
    header("Location: announcement-manage.php?error=Update failed");
}

$stmt->close();
exit;
