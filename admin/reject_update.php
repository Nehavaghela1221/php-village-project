<?php
require '../config/db.php';

/* ================= SERIAL ================= */
$serial_no = $_GET['serial_no'] ?? '';
if ($serial_no == '') {
    die("Invalid Request");
}

/* ================= CHECK PENDING ================= */
$stmt = $conn->prepare("
    SELECT id 
    FROM members 
    WHERE serial_no=? AND update_status='pending'
");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    die("No pending update found");
}

/* ================= REJECT UPDATE ================= */
/* IMPORTANT: DO NOT clear pending_data */
$rej = $conn->prepare("
    UPDATE members 
    SET update_status='rejected',
        updated_at=NOW()
    WHERE serial_no=?
");
$rej->bind_param("s", $serial_no);
$rej->execute();

/* ================= MARK NOTIFICATION READ ================= */
$notif = $conn->prepare("
    UPDATE admin_notifications 
    SET is_read=1 
    WHERE serial_no=?
");
$notif->bind_param("s", $serial_no);
$notif->execute();

/* ================= REDIRECT ================= */
header("Location: update_requests.php");
exit;
