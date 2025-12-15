<?php

require 'admin/auth.php';

require 'config/db.php';

/* ===== BASIC CHECK ===== */
if (!isset($_POST['file_type']) || empty($_FILES['file']['name'][0])) {
    header("Location: gallery-manage.php?upload_error=No+file+selected");
    exit;
}

$type  = $_POST['file_type'];
$files = $_FILES['file'];

$image_ext = ['jpg','jpeg','png','webp'];
$video_ext = ['mp4'];

$max_image_size = 2 * 1024 * 1024;   // 2MB
$max_video_size = 20 * 1024 * 1024;  // 20MB

$uploaded = false;
$error    = '';

/* ================= IMAGE UPLOAD ================= */
if ($type === 'image') {

    for ($i = 0; $i < count($files['name']); $i++) {

        if ($files['error'][$i] !== 0) {
            $error = "Upload error occurred";
            break;
        }

        $ext  = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        $size = $files['size'][$i];

        if (!in_array($ext, $image_ext)) {
            $error = "Only image files are allowed";
            break;
        }

        if ($size > $max_image_size) {
            $error = "Each image must be under 2MB";
            break;
        }

        $newName = time() . "_" . uniqid() . "." . $ext;
        $path = "uploads/gallery/images/" . $newName;

        if (move_uploaded_file($files['tmp_name'][$i], $path)) {
            $stmt = $conn->prepare(
                "INSERT INTO gallery (file_name, file_type) VALUES (?, 'image')"
            );
            $stmt->bind_param("s", $newName);
            $stmt->execute();
            $uploaded = true;
        }
    }
}

/* ================= VIDEO UPLOAD ================= */
elseif ($type === 'video') {

    /* Only first file allowed */
    if ($files['error'][0] !== 0) {
        $error = "Video upload error";
    } else {

        $ext  = strtolower(pathinfo($files['name'][0], PATHINFO_EXTENSION));
        $size = $files['size'][0];

        if (!in_array($ext, $video_ext)) {
            $error = "Only MP4 video allowed";
        }
        elseif ($size > $max_video_size) {
            $error = "Video must be under 20MB";
        }
        else {

            $newName = time() . "_" . uniqid() . "." . $ext;
            $path = "uploads/gallery/videos/" . $newName;

            if (move_uploaded_file($files['tmp_name'][0], $path)) {
                $stmt = $conn->prepare(
                    "INSERT INTO gallery (file_name, file_type) VALUES (?, 'video')"
                );
                $stmt->bind_param("s", $newName);
                $stmt->execute();
                $uploaded = true;
            }
        }
    }
}
else {
    $error = "Invalid file type selected";
}

/* ================= FINAL REDIRECT ================= */
if ($uploaded) {
    header("Location: gallery-manage.php?upload_success=1");
} else {
    header("Location: gallery-manage.php?upload_error=" . urlencode($error ?: "Upload failed"));
}
exit;
