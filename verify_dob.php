<?php
require 'config/db.php';

$serial = $_POST['serial_no'];
$dob = $_POST['dob'];

$q = $conn->query("SELECT id FROM members 
                   WHERE serial_no='$serial' AND dob='$dob'");

if($q->num_rows === 1){
    echo "success";
}else{
    echo "fail";
}
