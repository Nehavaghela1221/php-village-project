<?php
require 'config/db.php';

/* ================= MEMBER ================= */
$id = $_GET['id'] ?? 0;
$id = (int)$id;

$res = $conn->query("SELECT * FROM members WHERE id=$id");
if($res->num_rows == 0){
    die("Member not found");
}
$member = $res->fetch_assoc();
$serial_no = $member['serial_no'];

/* ================= FAMILY ================= */
$stmt = $conn->prepare("
    SELECT * FROM family_members 
    WHERE serial_no=? 
    ORDER BY id DESC
");
$stmt->bind_param("s",$serial_no);
$stmt->execute();
$family = $stmt->get_result();

/* ================= DELETE FAMILY ================= */
if(isset($_GET['delete'])){
    $fid = (int)$_GET['delete'];
    $d = $conn->prepare("DELETE FROM family_members WHERE id=?");
    $d->bind_param("i",$fid);
    $d->execute();
    header("Location: member_details.php?id=".$id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Member Full Details</title>
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<div class="container my-5">

<!-- ================= MEMBER DETAILS ================= -->
<h3 class="mb-4 text-primary">
<i class="bi bi-person-badge"></i> Member Details
</h3>

<div class="card shadow-sm mb-5">
<div class="card-body">
<div class="row">

<div class="col-md-4 text-center">
<img src="uploads/<?= $member['photo'] ?: 'no-user.png' ?>"
     class="img-fluid rounded mb-3"
     style="max-height:220px;">
</div>

<div class="col-md-8">
<table class="table table-borderless">
<tr><th>Serial No:</th><td><?= $member['serial_no'] ?></td></tr>
<tr><th>Full Name:</th><td><?= $member['full_name'] ?></td></tr>
<tr><th>Address:</th><td><?= $member['address'] ?></td></tr>
<tr><th>Area:</th><td><?= $member['area'] ?></td></tr>
<tr><th>City:</th><td><?= $member['city'] ?></td></tr>
<tr><th>Phone:</th><td><?= $member['phone'] ?></td></tr>
<tr><th>Shakh:</th><td><?= $member['shakh'] ?></td></tr>
<tr><th>Samaj:</th><td><?= $member['samaj'] ?></td></tr>
<tr><th>Family No:</th><td><?= $member['family_no'] ?></td></tr>
<tr><th>Marriage Status:</th><td><?= $member['marriage_status'] ?></td></tr>
<tr><th>Occupation:</th><td><?= $member['occupation'] ?></td></tr>
<tr><th>Business Address:</th><td><?= $member['business_address'] ?></td></tr>
</table>

<a href="members_list.php" class="btn btn-secondary btn-sm">
<i class="bi bi-arrow-left"></i> Back
</a>
</div>

</div>
</div>
</div>

<!-- ================= FAMILY MEMBERS ================= -->
<h4 class="mb-3 text-success">
<i class="bi bi-people-fill"></i> Family Members
</h4>

<?php if($family->num_rows == 0): ?>
<div class="alert alert-warning text-center">
No family members added
</div>
<?php endif; ?>

<?php while($f = $family->fetch_assoc()): ?>

<div class="card shadow mb-3">
<div class="card-body">
<div class="row align-items-center">

<div class="col-md-2 text-center">
<img src="uploads/family/<?= $f['photo'] ?: 'no-user.png' ?>"
style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
</div>

<div class="col-md-8">
<strong><?= htmlspecialchars($f['name']) ?></strong><br>
Relation: <?= $f['relation'] ?><br>
Age: <?= $f['age'] ?> |
Gender: <?= $f['gender'] ?><br>
<?php if(strtolower($f['gender']) == 'male'): ?>
    Mobile: <?= htmlspecialchars($f['mobile']) ?><br>
<?php else: ?>
    Mobile:  <span class="text-muted fst-italic">
        <i class="bi bi-shield-lock-fill text-danger me-1"></i>
        Can't see
    </span><br>
<?php endif; ?>
Profession: <?= $f['profession'] ?><br>
Address: <?= $f['address'] ?>,
<?= $f['area'] ?>,
<?= $f['city'] ?>
</div>

<div class="col-md-2 text-end">
<a href="?id=<?= $id ?>&delete=<?= $f['id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this family member?')">
<i class="bi bi-trash"></i>
</a>
</div>

</div>
</div>
</div>

<?php endwhile; ?>

</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
