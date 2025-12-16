<?php
require '../config/db.php';

$serial_no = $_GET['serial_no'] ?? '';
if ($serial_no == '') die("Invalid Request");

$rej = $conn->prepare("
    UPDATE members 
    SET 
        update_status='rejected',
        updated_at=NOW()
    WHERE serial_no=?
");
$rej->bind_param("s", $serial_no);
$rej->execute();

echo "Rows updated: ".$rej->affected_rows; // TEMP DEBUG
exit;
