<?php
require '../config/db.php';

/* ================= SERIAL ================= */
$serial_no = $_GET['serial_no'] ?? '';
if ($serial_no == '') die("Invalid Request");

/* ================= FETCH PENDING DATA ================= */
$stmt = $conn->prepare("
    SELECT pending_data 
    FROM members 
    WHERE serial_no=? AND update_status='pending'
");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die("No pending update found");
}

$data = json_decode($row['pending_data'], true);
if (!is_array($data)) {
    die("Invalid pending data");
}

/* ================= BUILD UPDATE QUERY ================= */
$fields = [];
$values = [];

foreach ($data as $key => $value) {
    $fields[] = "`$key`=?";
    $values[] = $value;
}

/* ================= FINAL UPDATE ================= */
$sql = "
    UPDATE members 
    SET " . implode(",", $fields) . ",
        pending_data=NULL,
        update_status='approved',
        updated_at=NOW()
    WHERE serial_no=?
";

$values[] = $serial_no;
$types = str_repeat("s", count($values));

$up = $conn->prepare($sql);
$up->bind_param($types, ...$values);
$up->execute();

/* ================= MARK NOTIFICATION READ ================= */
$conn->query("
    UPDATE admin_notifications 
    SET is_read=1 
    WHERE serial_no='$serial_no'
");

/* ================= REDIRECT ================= */
header("Location: update_requests.php");
exit;
