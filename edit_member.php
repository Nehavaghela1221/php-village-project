<?php
require 'config/db.php';

/* ================= GET SERIAL ================= */
$serial_no = $_GET['serial_no'] ?? $_POST['serial_no'] ?? '';
if($serial_no==''){
    die("<div class='alert alert-danger'>Invalid Member</div>");
}

/* ================= FETCH MEMBER ================= */
$stmt = $conn->prepare("SELECT * FROM members WHERE serial_no=?");
$stmt->bind_param("s",$serial_no);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if(!$member){
    die("<div class='alert alert-danger'>Member not found</div>");
}

/* ================= FETCH FAMILY MEMBERS (READ ONLY) ================= */
$fam = $conn->prepare("SELECT * FROM family_members WHERE serial_no=?");
$fam->bind_param("s",$serial_no);
$fam->execute();
$family = $fam->get_result();

/* ================= UPDATE REQUEST ================= */
$msg = "";
if(isset($_POST['update_member'])){

    $photo_path = $member['photo'] ?? '';

    if(!empty($_FILES['photo']['name'])){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir,0777,true);
        $photo_name = time().'_'.basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'],$target_dir.$photo_name);
        $photo_path = $photo_name;
    }

    $data = [
        'full_name'=>$_POST['full_name'],
        'dob'=>$_POST['dob'],
        'address'=>$_POST['address'],
        'area'=>$_POST['area'],
        'city'=>$_POST['city'],
        'phone'=>$_POST['phone'],
        'shakh'=>$_POST['shakh'],
        'samaj'=>$_POST['samaj'],
        'family_no'=>$_POST['family_no'],
        'marriage_status'=>$_POST['marriage_status'],
        'occupation'=>$_POST['occupation'],
        'business_address'=>$_POST['business_address'],
        'photo'=>$photo_path
    ];

    $json = json_encode($data);

    $u = $conn->prepare("
        UPDATE members 
        SET pending_data=?, update_status='pending' 
        WHERE serial_no=?
    ");
    $u->bind_param("ss",$json,$serial_no);
    $u->execute();

    $msg = "Update request sent to admin for approval";
}
/* ================= ADD FAMILY MEMBER (ONLY ADD) ================= */
if(isset($_POST['add_family'])){

    $photo = '';
    if(!empty($_FILES['family_photo']['name'])){
        $dir = "uploads/family/";
        if(!is_dir($dir)) mkdir($dir,0777,true);
        $photo = time().'_'.basename($_FILES['family_photo']['name']);
        move_uploaded_file($_FILES['family_photo']['tmp_name'],$dir.$photo);
    }

    $stmt = $conn->prepare("
        INSERT INTO family_members
        (serial_no, photo, name, relation, age, gender, mobile, profession, address, area, city)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssissssss",
        $serial_no,
        $photo,
        $_POST['family_name'],
        $_POST['relation'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['mobile'],
        $_POST['profession'],
        $_POST['address'],
        $_POST['area'],
        $_POST['city']
    );

    $stmt->execute();

    // 🔒 VERY IMPORTANT: STOP DUPLICATE INSERT ON REFRESH
    header("Location: edit_member.php?serial_no=".$serial_no);
    exit;
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Member</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<?php include 'header.php'; ?>

<div class="container my-5"> <!-- Right button -->
   <div class="text-end mb-3">
  <button type="button"
          class="btn text-white add-btn"
          data-bs-toggle="modal"
          data-bs-target="#addFamilyModal">
    <i class="bi bi-person-plus-fill"></i> Add Family Member
  </button>
</div>


<!-- ================= MAIN MEMBER EDIT ================= -->
<div class="card shadow">

  <div class="card-header bg-warning  fw-bold
              d-flex justify-content-between align-items-center" style="color:#6A1B9A">
    <!-- Left title -->
    <span>
      <i class="bi bi-pencil-square me-1"></i> Edit Main Member
    </span>
  </div>

<div class="card-body">

<?php if($msg): ?>
<div class="alert alert-warning text-center fw-bold"><?= $msg ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="serial_no" value="<?= htmlspecialchars($member['serial_no']) ?>">

<div class="row g-3">

<div class="col-md-12 text-center">
<img src="uploads/<?= $member['photo'] ?: 'no-user.png' ?>"
     class="rounded-circle mb-3"
     style="width:120px;height:120px;object-fit:cover;">
<input type="file" name="photo" class="form-control">
</div>

<div class="col-md-6">
<label class="form-label">Full Name</label>
<input type="text" name="full_name" class="form-control"
value="<?= htmlspecialchars($member['full_name']) ?>" required>
</div>

<div class="col-md-6">
<label class="form-label">Date of Birth</label>
<input type="date" name="dob" class="form-control"
value="<?= htmlspecialchars($member['dob']) ?>">
</div>

<div class="col-md-12">
<label class="form-label">Address</label>
<textarea name="address" class="form-control"><?= htmlspecialchars($member['address']) ?></textarea>
</div>

<div class="col-md-4">
<label class="form-label">Area</label>
<input type="text" name="area" class="form-control"
value="<?= htmlspecialchars($member['area']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">City</label>
<input type="text" name="city" class="form-control"
value="<?= htmlspecialchars($member['city']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Phone</label>
<input type="text" name="phone" class="form-control"
value="<?= htmlspecialchars($member['phone']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Shakh</label>
<input type="text" name="shakh" class="form-control"
value="<?= htmlspecialchars($member['shakh']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Samaj</label>
<input type="text" name="samaj" class="form-control"
value="<?= htmlspecialchars($member['samaj']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Family No</label>
<input type="text" name="family_no" class="form-control"
value="<?= htmlspecialchars($member['family_no']) ?>">
</div>

<div class="col-md-6">
<label class="form-label">Marriage Status</label>
<input type="text" name="marriage_status" class="form-control"
value="<?= htmlspecialchars($member['marriage_status']) ?>">
</div>

<div class="col-md-6">
<label class="form-label">Occupation</label>
<input type="text" name="occupation" class="form-control"
value="<?= htmlspecialchars($member['occupation']) ?>">
</div>

<div class="col-md-12">
<label class="form-label">Business Address</label>
<textarea name="business_address" class="form-control"><?= htmlspecialchars($member['business_address']) ?></textarea>
</div>

</div>
  
<div class="text-center mt-4">
<button type="submit" name="update_member" class="btn btn add-btn px-5 fw-bold text-white">
Send Update Request
</button>

<a href="members_list.php" class="btn add-btn ms-2">
  <i class="bi bi-arrow-left-circle me-1"></i> Back
</a>


</div>

</form>
</div>
</div>


<!-- ADD FAMILY MEMBER MODAL (NEW ONLY) -->
<div class="modal fade" id="addFamilyModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data">

        <div class="modal-header card-header">
          <h5 class="modal-title fw-bold">
            <i class="bi bi-people-fill me-2"></i>
            Add Family Member
          </h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">

          <input type="file" name="family_photo" class="form-control">

          <input type="text" name="family_name" class="form-control"
                 placeholder="Name" required>

          <select name="relation" class="form-select" required>
            <option value="">Relation</option>
            <option>Father</option>
            <option>Mother</option>
            <option>Wife</option>
            <option>Husband</option>
            <option>Son</option>
            <option>Daughter</option>
            <option>Brother</option>
            <option>Sister</option>
            <option>Other</option>
          </select>

          <input type="number" name="age" class="form-control" placeholder="Age">

          <select name="gender" class="form-select">
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>

          <input type="text" name="mobile" class="form-control" placeholder="Mobile">
          <input type="text" name="profession" class="form-control" placeholder="Profession">
          <textarea name="address" class="form-control" placeholder="Address"></textarea>
          <input type="text" name="area" class="form-control" placeholder="Area">
          <input type="text" name="city" class="form-control" placeholder="City">

        </div>
      


        <div class="modal-footer">
          <button type="submit"
            name="add_family"
            class="btn add-btn w-100">
            <i class="bi bi-save me-1"></i> Save
          </button>

        </div>

      </form>

    </div>
  </div>
</div>


</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
