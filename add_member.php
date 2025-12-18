<?php
require 'admin/auth.php'; 
require 'config/db.php';
$msg = "";

/* ===== NEXT AUTO ID (DISPLAY ONLY) ===== */
$res = $conn->query("SELECT MAX(id) AS maxid FROM members");
$row = $res->fetch_assoc();
$next_id = ($row['maxid'] ?? 0) + 1;

/* ===== CREATE UPLOAD FOLDER ===== */
if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}


/* ===== FORM SUBMIT ===== */
if (isset($_POST['submit'])) {

    $serial_no = $_POST['serial_no'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $family_no = $_POST['family_no'];
    $business_address = $_POST['business_address'];

    $shakh = ($_POST['shakh'] === 'Other') ? $_POST['shakh_other'] : $_POST['shakh'];
    $samaj = ($_POST['samaj'] === 'Other') ? $_POST['samaj_other'] : $_POST['samaj'];
    $marriage_status = ($_POST['marriage_status'] === 'Other') ? $_POST['marriage_status_other'] : $_POST['marriage_status'];
    $occupation = ($_POST['occupation'] === 'Other') ? $_POST['occupation_other'] : $_POST['occupation'];

    /* PHONE VALIDATION */
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $msg = "Phone number must be 10 digits";
    } else {

        /* PHOTO UPLOAD */
        $photo = $_FILES['photo']['name'];
        $tmp   = $_FILES['photo']['tmp_name'];
        $size  = $_FILES['photo']['size'];
        $ext   = strtolower(pathinfo($photo, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            $msg = "Only JPG, PNG, WEBP allowed";
        } elseif ($size > 2097152) {
            $msg = "Photo must be under 2MB";
        } else {

            $new_photo = time() . "_" . uniqid() . "." . $ext;

            if (move_uploaded_file($tmp, "uploads/".$new_photo)) {

                $sql = "INSERT INTO members
                (serial_no, photo, full_name, dob, address, area, city, phone, shakh, samaj, family_no, marriage_status, occupation, business_address)
                VALUES
                ('$serial_no','$new_photo','$full_name','$dob','$address','$area','$city','$phone','$shakh','$samaj','$family_no','$marriage_status','$occupation','$business_address')";

                if ($conn->query($sql)) {
                    header("Location: ".$_SERVER['PHP_SELF']."?msg=success");
                    exit;
                } else {
                    $msg = "Database Error";
                }
            } else {
                $msg = "Photo upload failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Member</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<link href="/devinapura/assets/css/main.css" rel="stylesheet">
</head>

<body>
 <!-- NAVBAR -->
  <div class="admin-navbar">
    <div class="admin-navbar-inner">
      <h2 style="color: #f0ab0a;">Welcome Admin</h2>
      <a href="admin/logout.php" class="exit-btn">Exit Admin</a>
    </div>
  </div>

<div class="container my-5">
<div class="card shadow-lg">

<div class="card-header">
  <h4><i class="bi bi-person-plus-fill"></i> Add New Member</h4>
  <small>Member Registration Fill Carefully</small>
</div>

<div class="card-body p-4">

<form method="post" enctype="multipart/form-data" id="memberForm">
   <div class="col-md-4">
    <label class="form-label">Member ID</label>
    <input type="text" value="<?= $next_id ?>" class="form-control" readonly>
  </div>

<!-- BASIC INFO -->
<div class="section-title">Basic Information</div>
<div class="row g-3">
 <div class="col-md-4">
  <label class="form-label">Serial Number *</label>
  <input type="text"
         name="serial_no"
         class="form-control"
         placeholder="Enter 4 Digit Serial (e.g. 0001)"
         maxlength="4"
         pattern="\d{4}"
         title="Serial number must be exactly 4 digits"
         required>
</div>
<script>
document.querySelector('[name="serial_no"]').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
});
</script>

  <div class="col-md-8">
    <label class="form-label">Full Name *</label>
    <input type="text" name="full_name" class="form-control" required>
  </div>
    <div class="col-md-4">
    <label class="form-label">Date of Birth *</label>
    <input type="date" name="dob" class="form-control" required
            max="<?= date('Y-m-d'); ?>">
    </div>

  <div class="col-md-8">
    <label class="form-label">Photo *</label>
      <input type="file" name="photo" class="form-control" accept="image/*" required>
      <small class="text-muted">Max 2MB (JPG/PNG/WEBP)</small>
  </div>
</div>

<!-- CONTACT -->
<div class="section-title">Contact Details</div>
<label class="form-label">Address</label>
<textarea name="address" class="form-control mb-3" rows="2" placeholder="Full Address" required></textarea>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Area</label>
    <input name="area" class="form-control" placeholder="Area">
  </div>
  <div class="col-md-4">
    <label class="form-label">City</label>
    <input name="city" class="form-control" placeholder="City">
  </div>

  <div class="col-md-4">
    <label class="form-label">Phone Number</label>
    <input name="phone" class="form-control" maxlength="10" placeholder="10 Digit Phone" required>
  </div>
</div>

<!-- COMMUNITY -->
<div class="section-title">Community Details</div>
<div class="row g-3">
  <div class="col-md-4">
    <select name="shakh" id="shakh" class="form-select" required onchange="toggleOther('shakh')">
      <option value="">Select Shakh</option>
      <option>Shakh 1</option>
      <option>Other</option>
    </select>
    <input id="shakh_other" name="shakh_other" class="form-control d-none mt-2" placeholder="Other Shakh">
  </div>

  <div class="col-md-4">
    <select name="samaj" id="samaj" class="form-select" required onchange="toggleOther('samaj')">
      <option value="">Select Samaj</option>
      <option>Samaj A</option>
      <option>Other</option>
    </select>
    <input id="samaj_other" name="samaj_other" class="form-control d-none mt-2" placeholder="Other Samaj">
  </div>

  <div class="col-md-4">
<input name="family_no" class="form-control" placeholder="Family Number" maxlength="2" pattern="\d{1,2}" title="Enter up to 2 digits only">
  </div>
</div>

<!-- PERSONAL -->
<div class="section-title">Personal & Work</div>
<div class="row g-3">
  <div class="col-md-4">
    <select name="marriage_status" id="marriage_status" class="form-select" required onchange="toggleOther('marriage_status')">
      <option value="">Marriage Status</option>
      <option>Single</option>
      <option>Married</option>
      <option>Other</option>
    </select>
    <input id="marriage_status_other" name="marriage_status_other" class="form-control d-none mt-2" placeholder="Other Status">
  </div>

  <div class="col-md-4">
    <select name="occupation" id="occupation" class="form-select" required onchange="toggleOther('occupation')">
      <option value="">Occupation</option>
      <option>Business</option>
      <option>Service</option>
      <option>Other</option>
    </select>
    <input id="occupation_other" name="occupation_other" class="form-control d-none mt-2" placeholder="Other Occupation">
  </div>

  <div class="col-md-4">
    <input name="business_address" class="form-control" placeholder="Business Address">
  </div>
</div>

<!-- SAVE -->
<div class="text-center mt-4">
<button type="button" class="btn add-btn btn-save"
data-bs-toggle="modal" data-bs-target="#confirmModal">
<i class="bi bi-save"></i> Save Member
</button>
</div>

<!-- CONFIRM MODAL -->
<div class="modal fade" id="confirmModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header card-header text-white">
<h5 class="modal-title"><i class="bi bi-check-circle"></i> Confirm Save</h5>
</div>
<div class="modal-body text-center">
Are you sure you want to save this member?
</div>
<div class="modal-footer justify-content-center">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" name="submit" class="btn btn-warning">Yes, Save</button>
</div>
</div>
</div>
</div>

</form>

</div>
</div>
</div>

<script>
    // Restrict Family Number to digits only, max 2
const familyInput = document.querySelector('[name="family_no"]');
familyInput.addEventListener('input', function() {
  this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);
});

function toggleOther(f){
  let s=document.getElementById(f);
  let i=document.getElementById(f+'_other');
  if(s.value==='Other'){
    i.classList.remove('d-none');
    i.required=true;
  }else{
    i.classList.add('d-none');
    i.required=false;
  }
}

/* PHONE VALIDATION */
document.querySelector('[name="phone"]').addEventListener('input',function(){
  this.value=this.value.replace(/[^0-9]/g,'').slice(0,10);
});
</script>

</body>
</html>