<?php
require 'config/db.php';
if(isset($_POST['update_member'])){

    // photo upload logic (tamaro same rehse)
    $photo_path = $member['photo'];
    if(!empty($_FILES['photo']['name'])){
        $target_dir="uploads/";
        $photo_name=time().'_'.basename($_FILES['photo']['name']);
        $target_file=$target_dir.$photo_name;
        if(move_uploaded_file($_FILES['photo']['tmp_name'],$target_file)){
            $photo_path = $photo_name;
        }
    }

    // pending data store
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
        'status'=>$_POST['status'],
        'photo'=>$photo_path
    ];

    $json = json_encode($data);

    $stmt = $conn->prepare("
        UPDATE members 
        SET pending_data=?, update_status='pending' 
        WHERE serial_no=?
    ");
    $stmt->bind_param("ss",$json,$serial_no);
    $stmt->execute();

    echo "<p style='color:orange'>Update request sent to admin</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Members List</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

</head>
<body>

<div class="container my-5">
  <h3 class="mb-4 text-primary fw-bold">Members List</h3>

  <div class="table-responsive shadow-sm rounded p-3 bg-white">
    <table id="membersTable" class="table table-striped table-bordered align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>Serial No</th>
          <th>Photo</th>
          <th>Name</th>
          <th>Area</th>
          <th>City</th>
          <th>Phone</th>
          <th>Shakh</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = $conn->query("SELECT * FROM members ORDER BY id DESC");
        while($row = $res->fetch_assoc()):
        ?>
        <tr>
          <td class="py-3"><?= $row['serial_no'] ?></td>
          <td class="text-center py-3">
            <img src="uploads/<?= $row['photo'] ?>" class="rounded-circle" style="width:50px; height:50px;" alt="Photo">
          </td>
          <td class="py-3"><?= $row['full_name'] ?></td>
          <td class="py-3"><?= $row['area'] ?></td>
          <td class="py-3"><?= $row['city'] ?></td>
          <td class="py-3"><?= $row['phone'] ?></td>
          <td class="py-3"><?= $row['shakh'] ?></td>
          <td class="py-3">
            <a href="member_details.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm me-1">
              <i class="bi bi-eye"></i> Details
            </a>
          <button 
  class="btn btn-warning btn-sm"
  onclick="openDobModal('<?= $row['serial_no'] ?>')">
  <i class="bi bi-pencil-square"></i> Edit
</button>


          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<!-- DOB VERIFY MODAL -->
<div class="modal fade" id="dobModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">
          <i class="bi bi-shield-lock"></i> Verify Birthdate
        </h5>
      </div>

      <div class="modal-body text-center">
        <input type="hidden" id="serial_no">
        <label class="form-label">Enter Birthdate</label>
        <input type="date" id="dob" class="form-control" required>
        <div id="dobError" class="text-danger mt-2 d-none">
          Birthdate does not match!
        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-warning" onclick="verifyDob()">Verify</button>
      </div>

    </div>
  </div>
</div>
<script>
function openDobModal(serial){
  document.getElementById('serial_no').value = serial;
  document.getElementById('dob').value = '';
  document.getElementById('dobError').classList.add('d-none');
  new bootstrap.Modal(document.getElementById('dobModal')).show();
}

function verifyDob(){
  let serial = document.getElementById('serial_no').value;
  let dob = document.getElementById('dob').value;

  if(!dob){
    alert("Please enter birthdate");
    return;
  }

  fetch('verify_dob.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `serial_no=${serial}&dob=${dob}`
  })
  .then(res => res.text())
  .then(resp => {
    if(resp === 'success'){
      window.location.href = "edit_member.php?serial_no=" + serial;
    }else{
      document.getElementById('dobError').classList.remove('d-none');
    }
  });
}
</script>

<script>
$(document).ready(function() {
    $('#membersTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
             "<'col-sm-6 d-flex justify-content-end align-items-center'B'f ms-3>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i> Export',
                className: 'btn btn-primary btn-sm me-3 mt-1',
                buttons: [
                    { extend: 'excelHtml5', text: 'Excel' },
                    { extend: 'pdfHtml5', text: 'PDF' },
                    { extend: 'csvHtml5', text: 'CSV' },
                    { extend: 'print', text: 'Print' }
                ]
            }
        ]
    });
});
</script>

</body>
</html>
