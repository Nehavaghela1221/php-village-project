<?php
require 'config/db.php';

$view     = $_GET['view'] ?? '';
$relation = $_GET['relation'] ?? '';

/* ================= COUNTS ================= */
$mainCount      = $conn->query("SELECT COUNT(*) c FROM members")->fetch_assoc()['c'];
$familyCount    = $conn->query("SELECT COUNT(*) c FROM family_members")->fetch_assoc()['c'];
$wifeCount      = $conn->query("SELECT COUNT(*) c FROM family_members WHERE relation='Wife'")->fetch_assoc()['c'];
$sonCount       = $conn->query("SELECT COUNT(*) c FROM family_members WHERE relation='Son'")->fetch_assoc()['c'];
$daughterCount  = $conn->query("SELECT COUNT(*) c FROM family_members WHERE relation='Daughter'")->fetch_assoc()['c'];

$data = null;

/* ================= MAIN MEMBERS ================= */
if ($view === 'main') {
    $data = $conn->query("
        SELECT 
            serial_no,
            full_name,
            'Main' AS relation,
            phone,
            NULL AS mobile,
            NULL AS gender,
            photo
        FROM members
        ORDER BY id DESC
    ");
}

/* ================= MAIN + FAMILY ================= */
if ($view === 'family') {
    $data = $conn->query("
        SELECT 
            serial_no,
            full_name,
            'Main' AS relation,
            phone,
            NULL AS mobile,
            NULL AS gender,
            photo
        FROM members

        UNION ALL

        SELECT 
            serial_no,
            name AS full_name,
            relation,
            NULL AS phone,
            mobile,
            gender,
            photo
        FROM family_members
    ");
}

/* ================= RELATION FILTER ================= */
if ($view === 'relation' && in_array($relation, ['Wife','Son','Daughter'])) {
    $stmt = $conn->prepare("
        SELECT 
            fm.serial_no,
            fm.name AS full_name,
            fm.relation,
            NULL AS phone,
            fm.mobile,
            fm.gender,
            fm.photo
        FROM family_members fm
        WHERE fm.relation = ?
    ");
    $stmt->bind_param("s", $relation);
    $stmt->execute();
    $data = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Members Directory</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<style>
.member-img{
  width:50px;
  height:50px;
  border-radius:50%;
  object-fit:cover;
}
</style>
</head>

<body>
<div class="container my-4">

<h3 class="text-center mb-4 text-primary">
<i class="bi bi-people-fill"></i> Members Directory
</h3>

<!-- BUTTONS -->
<div class="row mb-3 text-center">
  <div class="col-md-4">
    <a href="?view=main" class="btn btn-outline-primary w-100">
      Main Members <span class="badge bg-dark"><?= $mainCount ?></span>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?view=family" class="btn btn-outline-success w-100">
      Main + Family <span class="badge bg-dark"><?= $familyCount ?></span>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?view=relation" class="btn btn-outline-warning w-100">
      Relation Filter
    </a>
  </div>
</div>

<?php if($view === 'relation'): ?>
<div class="text-center mb-3">
  <a href="?view=relation&relation=Wife" class="btn btn-danger">Wife <?= $wifeCount ?></a>
  <a href="?view=relation&relation=Son" class="btn btn-primary">Son <?= $sonCount ?></a>
  <a href="?view=relation&relation=Daughter" class="btn btn-warning">Daughter <?= $daughterCount ?></a>
</div>
<?php endif; ?>

<?php if($view && $data instanceof mysqli_result): ?>
<table id="exportTable" class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
  <th>Serial</th>
  <th>Photo</th>
  <th>Name</th>
  <th>Relation</th>
  <th>Mobile</th>
</tr>
</thead>
<tbody>

<?php while($r = $data->fetch_assoc()): ?>
<tr>
   <td><?= $r['serial_no'] ?></td>
  <td>
    <img src="uploads/<?= $r['photo'] ?: 'no-user.png' ?>" class="member-img">
  </td>

  <td><?= htmlspecialchars($r['full_name']) ?></td>

  <td><?= $r['relation'] ?></td>

 

  <td>
    <?php
    // ✅ MAIN MEMBER → ALWAYS SHOW
    if ($r['relation'] === 'Main') {
        echo $r['phone'];
    }
    // ✅ FAMILY MEMBER
    else {
        if (strtolower($r['gender']) === 'female') {
            echo '<i class="bi bi-shield-lock-fill text-muted"></i> Can\'t see';
        } else {
            echo $r['mobile'];
        }
    }
    ?>
  </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
<?php endif; ?>

</div>

<script>
$(document).ready(function () {
  $('#exportTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      { extend:'excelHtml5', text:'📊 Excel', className:'btn btn-success' },
      { extend:'pdfHtml5', text:'📄 PDF', className:'btn btn-danger' },
      { extend:'print', text:'🖨️ Print' }
    ]
  });
});
</script>

</body>
</html>
