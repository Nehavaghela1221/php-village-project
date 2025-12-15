<?php
require 'config/db.php';

$id = $_GET['id'] ?? 0;
$id = (int)$id;
$res = $conn->query("SELECT * FROM members WHERE id=$id");
if($res->num_rows == 0){
    die("Member not found");
}
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Member Details</title>
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container my-5">
  <h3 class="mb-4">Member Details: <?= $row['full_name'] ?></h3>

  <div class="card shadow-sm p-3">
    <div class="row">
      <div class="col-md-4 text-center">
        <img src="uploads/<?= $row['photo'] ?>" class="img-fluid rounded mb-3" alt="Member Photo">
      </div>
      <div class="col-md-8">
        <table class="table table-borderless">
          <tr><th>Serial No:</th><td><?= $row['serial_no'] ?></td></tr>
          <tr><th>Full Name:</th><td><?= $row['full_name'] ?></td></tr>
          <tr><th>Address:</th><td><?= $row['address'] ?></td></tr>
          <tr><th>Area:</th><td><?= $row['area'] ?></td></tr>
          <tr><th>City:</th><td><?= $row['city'] ?></td></tr>
          <tr><th>Phone:</th><td><?= $row['phone'] ?></td></tr>
          <tr><th>Shakh:</th><td><?= $row['shakh'] ?></td></tr>
          <tr><th>Samaj:</th><td><?= $row['samaj'] ?></td></tr>
          <tr><th>Family No:</th><td><?= $row['family_no'] ?></td></tr>
          <tr><th>Marriage Status:</th><td><?= $row['marriage_status'] ?></td></tr>
          <tr><th>Occupation:</th><td><?= $row['occupation'] ?></td></tr>
          <tr><th>Business Address:</th><td><?= $row['business_address'] ?></td></tr>
        </table>
        <a href="members_list.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
