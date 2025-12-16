<?php
require 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Members List</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<style>
.pin-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.6);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:9999;
}
.pin-modal{
  background:#fff;
  padding:25px;
  border-radius:10px;
  width:320px;
  text-align:center;
}
</style>
</head>

<body>

<div class="container my-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-primary mb-0">Members List</h3>
    <button class="btn btn-success" onclick="openPinModal()">
      <i class="bi bi-person-plus-fill"></i> Add Member
    </button>
  </div>

  <div class="table-responsive shadow rounded bg-white p-3">
    <table id="membersTable" class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Serial No</th>
          <th>Photo</th>
          <th>Name</th>
          <th>Area</th>
          <th>City</th>
          <th>Phone</th>
          <th>Shakh</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

<?php
$res = $conn->query("SELECT * FROM members ORDER BY id DESC");
while($row = $res->fetch_assoc()):
?>

<tr>
  <td><?= htmlspecialchars($row['serial_no']) ?></td>

  <td class="text-center">
    <img src="uploads/<?= $row['photo'] ?: 'no-user.png' ?>"
         class="rounded-circle"
         style="width:50px;height:50px;object-fit:cover;">
  </td>

  <td><?= htmlspecialchars($row['full_name']) ?></td>
  <td><?= htmlspecialchars($row['area']) ?></td>
  <td><?= htmlspecialchars($row['city']) ?></td>
  <td><?= htmlspecialchars($row['phone']) ?></td>
  <td><?= htmlspecialchars($row['shakh']) ?></td>

  <!-- STATUS -->
<td class="text-center">
<?php
$status = $row['update_status'];

if ($status === 'pending') {
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}
elseif ($status === 'rejected') {
    echo "<span class='badge bg-danger'>Rejected</span>";
}
else {
    // approved OR empty OR null
    echo "<span class='badge bg-success'>Approved</span>";
}
?>
</td>

  <!-- ACTION -->
  <td class="text-center">

    <!-- VIEW always -->
    <a href="member_details.php?id=<?= $row['id'] ?>" 
       class="btn btn-info btn-sm">
      <i class="bi bi-eye"></i>
    </a>
    <!-- EDIT only when NOT pending -->
    <?php if($row['update_status'] !== 'pending'): ?>
      <button class="btn btn-warning btn-sm"
              onclick="openDobModal('<?= $row['serial_no'] ?>')">
        <i class="bi bi-pencil-square"></i>
      </button>
    <?php endif; ?>

  </td>
</tr>

<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ================= PIN MODAL ================= -->
<div id="pinOverlay" class="pin-overlay">
  <div class="pin-modal">
    <h5>Admin PIN</h5>
    <input type="password" id="adminPin" class="form-control my-2">
    <button class="btn btn-success w-100" onclick="verifyPin()">Verify</button>
    <p id="pinError" class="text-danger mt-2"></p>
  </div>
</div>

<!-- ================= DOB MODAL ================= -->
<div class="modal fade" id="dobModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Verify DOB</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" id="serial_no">
        <input type="date" id="dob" class="form-control">
        <div id="dobError" class="text-danger mt-2 d-none">DOB does not match</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" onclick="verifyDob()">Verify</button>
      </div>
    </div>
  </div>
</div>

<script>
function openPinModal(){
  document.getElementById('pinOverlay').style.display='flex';
}
function verifyPin(){
  fetch("admin/verify-pin.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:"pin="+adminPin.value
  })
  .then(r=>r.text())
  .then(res=>{
    if(res.trim()==="success"){
      location.href="add_member.php";
    }else{
      pinError.innerText="Wrong PIN";
    }
  });
}

function openDobModal(serial){
  document.getElementById('serial_no').value=serial;
  new bootstrap.Modal(document.getElementById('dobModal')).show();
}

function verifyDob(){
  fetch("verify_dob.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`serial_no=${serial_no.value}&dob=${dob.value}`
  })
  .then(r=>r.text())
  .then(res=>{
    if(res.trim()==="success"){
      location.href="edit_member.php?serial_no="+serial_no.value;
    }else{
      dobError.classList.remove('d-none');
    }
  });
}

$(function(){
  $('#membersTable').DataTable({
    pageLength:10,
    buttons:['excel','pdf','csv','print'],
    dom:"<'row mb-3'<'col-sm-6'l><'col-sm-6 text-end'Bf>>tr<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>"
  });
});
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DataTables core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>

<!-- 🔥 REQUIRED FOR EXCEL -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- 🔥 REQUIRED FOR PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

</body>
</html>
