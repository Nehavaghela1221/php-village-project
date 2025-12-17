<?php
require 'config/db.php';

/* ================= MEMBER ================= */
$id = (int)($_GET['id'] ?? 0);

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

/* ================= DELETE FAMILY (DOB VERIFY) ================= */
if(isset($_GET['delete'], $_GET['verify_dob'])){
    $fid = (int)$_GET['delete'];
    $enteredDob = $_GET['verify_dob'];

    // delete only if MAIN MEMBER DOB matches
    if($enteredDob === $member['dob']){
        $d = $conn->prepare("DELETE FROM family_members WHERE id=?");
        $d->bind_param("i",$fid);
        $d->execute();
    }

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
<link href="assets/css/main.css" rel="stylesheet">
</head>

<body>
<?php include 'header.php'; ?>

<div class="container my-5">

<h3 class="mb-4">
<i class="bi bi-person-badge-fill"></i> Member Details
</h3>

<!-- ================= PHOTO + DETAILS ================= -->
<div class="row g-4 mb-5">

<div class="col-md-4">
    <div class="photo-box">
        <img src="uploads/<?= $member['photo'] ?: 'no-user.png' ?>">
        <h5><?= $member['full_name'] ?></h5>
        <div class="small">Serial No: <?= $member['serial_no'] ?></div>
    </div>
</div>

<div class="col-md-8">
<div class="table-responsive">
<table class="table table-bordered align-middle member-table">
<tr><th>Phone</th><td><?= $member['phone'] ?></td></tr>
<tr><th>Address</th><td><?= $member['address'] ?></td></tr>
<tr><th>Area</th><td><?= $member['area'] ?></td></tr>
<tr><th>City</th><td><?= $member['city'] ?></td></tr>
<tr><th>Shakh</th><td><?= $member['shakh'] ?></td></tr>
<tr><th>Samaj</th><td><?= $member['samaj'] ?></td></tr>
<tr><th>Family No</th><td><?= $member['family_no'] ?></td></tr>
<tr><th>Marriage Status</th><td><?= $member['marriage_status'] ?></td></tr>
<tr><th>Occupation</th><td><?= $member['occupation'] ?></td></tr>
<tr><th>Business Address</th><td><?= $member['business_address'] ?></td></tr>
</table>
</div>
</div>

</div>

<!-- ================= FAMILY MEMBERS ================= -->
<h4 class="mb-3">
<i class="bi bi-people-fill"></i> Family Members
</h4>

<?php if($family->num_rows == 0): ?>
<div class="alert alert-warning text-center">No family members added</div>
<?php else: ?>

<div class="table-responsive">
<table class="table table-bordered align-middle family-table">
<thead>
<tr>
    <th>Photo</th>
    <th>Name</th>
    <th>Relation</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Mobile</th>
    <th>Profession</th>
    <th>Address</th>
    <th width="80">Action</th>
</tr>
</thead>
<tbody>

<?php while($f = $family->fetch_assoc()): ?>
<tr>
<td class="text-center">
    <img src="uploads/family/<?= $f['photo'] ?: 'no-user.png' ?>" class="family-photo">
</td>
<td><?= htmlspecialchars($f['name']) ?></td>
<td><?= $f['relation'] ?></td>
<td><?= $f['age'] ?></td>
<td><?= $f['gender'] ?></td>
<td>
<?php if(strtolower($f['gender']) == 'male'): ?>
    <?= htmlspecialchars($f['mobile']) ?>
<?php else: ?>
    <span class="text-muted fst-italic">
        <i class="bi bi-shield-lock-fill text-danger"></i> Hidden
    </span>
<?php endif; ?>
</td>
<td><?= $f['profession'] ?></td>
<td><?= $f['address'] ?>, <?= $f['area'] ?>, <?= $f['city'] ?></td>
<td class="text-center">
    <button
        class="btn btn-danger btn-sm"
        onclick="openDeleteModal(<?= $f['id'] ?>,'<?= $member['dob'] ?>')">
        <i class="bi bi-trash"></i>
    </button>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>
<?php endif; ?>

<div class="mt-3 text-end">
    <a href="members_list.php" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

</div>

<!-- ================= DOB VERIFY MODAL ================= -->
<div class="modal fade" id="dobModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header card-header">
        <h5 class="modal-title text-danger">
          <i class="bi bi-shield-lock-fill"></i> DOB Verification
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="delete_id">
        <input type="hidden" id="member_dob">

        <label class="form-label">Enter Main Member DOB</label>
        <input type="date" id="entered_dob" class="form-control">

        <div class="text-danger mt-2 d-none" id="dobError">
          ❌ DOB does not match
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger btn-sm" onclick="verifyDOB()">
          Verify & Delete
        </button>
      </div>

    </div>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
function openDeleteModal(fid, memberDob){
    document.getElementById('delete_id').value = fid;
    document.getElementById('member_dob').value = memberDob;
    document.getElementById('entered_dob').value = '';
    document.getElementById('dobError').classList.add('d-none');

    new bootstrap.Modal(document.getElementById('dobModal')).show();
}

function verifyDOB(){
    let entered = document.getElementById('entered_dob').value;
    let realDob = document.getElementById('member_dob').value;
    let fid = document.getElementById('delete_id').value;

    if(entered === realDob){
        window.location.href =
          "?id=<?= $id ?>&delete=" + fid + "&verify_dob=" + entered;
    }else{
        document.getElementById('dobError').classList.remove('d-none');
    }
}
</script>

</body>
</html>
