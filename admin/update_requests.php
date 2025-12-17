<?php
require '../config/db.php';

/* ================= FETCH PENDING UPDATES ================= */
$sql = "
    SELECT serial_no, full_name, updated_at 
    FROM members 
    WHERE update_status = 'pending'
    ORDER BY updated_at DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pending Update Requests</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/devinapura/assets/css/main.css" rel="stylesheet">

<style>
/* ===== GLOBAL RESET ===== */

body{
    margin:0;
    padding:0;
    overflow-x:hidden; /* 🔥 IMPORTANT */
}

/* ===== PAGE TITLE ===== */
h2{
    text-align:center;
    margin:20px 10px;
     color:#6A1B9A;  /* Bootstrap purple */
}

.table-wrap{
    width:calc(100% - 80px);
    margin:10px auto;   /* left-right visible */
    padding:0 10px;
    overflow-x:hidden;
}

/* ===== TABLE ===== */
table{
    width:100%;              /* 🔥 FIX */
    border-collapse:collapse;
    background:#fff;
}

/* ===== CELLS ===== */
th,td{
    padding:12px;
    border:1px solid #ccc;
    text-align:left;
    white-space:nowrap;
}

/* ===== HEADER ===== */
th{
   background: linear-gradient(135deg, #f0ab0a, #d37810);
    color:#fff;
}

/* ===== BUTTON ===== */
a{
    text-decoration:none;
    padding:6px 12px;
    background:#6A1B9A;
    color:#fff;
    border-radius:4px;
    display:inline-block;
}

/* ===== EMPTY ===== */
.empty{
    text-align:center;
    padding:30px;
    color:#777;
}

/* ===== MOBILE ONLY ===== */
@media(max-width:768px){

    body{
        overflow-x:hidden; /* body never scrolls */
    }

    .table-wrap{
        overflow-x:auto;   /* ✅ ONLY table scrolls */
    }

    table{
        min-width:700px;   /* force horizontal scroll */
    }
}
</style>
</head>

<body>
 <!-- NAVBAR -->
  <div class="admin-navbar">
    <div class="admin-navbar-inner">
      <h2 style="color: #f0ab0a;">Welcome Admin</h2>
      <a href="logout.php" class="exit-btn">Exit Admin</a>
    </div>
  </div>
<h2>Pending Member Update Requests</h2>

<div class="table-wrap ">
<table>
<tr>
    <th>Serial No</th>
    <th>Member Name</th>
    <th>Requested On</th>
    <th>Action</th>
</tr>

<?php if($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['serial_no']) ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['updated_at']) ?></td>
            <td>
                <a href="view_update.php?serial_no=<?= urlencode($row['serial_no']) ?>">
                    View & Compare
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="empty">No pending update requests</td>
    </tr>
<?php endif; ?>

</table>
</div>

</body>
</html>
