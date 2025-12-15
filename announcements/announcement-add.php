<?php
require '../admin/auth.php';   // 🔐 common admin protection
require '../config/db.php';

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$expire_at   = $_POST['expire_at'] ?? '';

if ($title === '' || $description === '' || $expire_at === '') {
    header("Location: announcement-manage.php?error=All fields required");
    exit;
}

/* INSERT (created_at auto handled by DB) */
$stmt = $conn->prepare(
    "INSERT INTO announcements (title, description, expire_at)
     VALUES (?, ?, ?)"
);

$stmt->bind_param("sss", $title, $description, $expire_at);

if ($stmt->execute()) {
    header("Location: announcement-manage.php?success=1");
} else {
    header("Location: announcement-manage.php?error=Insert failed");
}

$stmt->close();
exit;
