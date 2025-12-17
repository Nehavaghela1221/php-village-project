<?php
require '../config/db.php';

/* ================= SERIAL ================= */
$serial_no = $_GET['serial_no'] ?? '';
if ($serial_no == '') die("Invalid Request");

/* ================= FETCH MEMBER ================= */
$stmt = $conn->prepare("SELECT * FROM members WHERE serial_no=?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) die("Member not found");

/* ================= PENDING DATA ================= */
if (empty($member['pending_data'])) {
    die("No pending update found");
}

$oldData = $member;
$newData = json_decode($member['pending_data'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Compare Member Update</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/devinapura/assets/css/main.css" rel="stylesheet">
</head>

<body>

 <!-- NAVBAR -->
  <div class="admin-navbar">
    <div class="admin-navbar-inner">
      <h2 style="color: #f0ab0a;">Welcome Admin</h2>
      <a href="logout.php" class="exit-btn">Exit Admin</a>
    </div>
  </div>

<div class="container my-4" >

    <h3 class="text-center text-purple fw-bold mb-4" style="color:#6f42c1;">
        Compare Member Update (<?= htmlspecialchars($serial_no) ?>)
    </h3>

    <!-- TABLE CARD -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-warning">
                        <tr>
                            <th width="25%">Field</th>
                            <th width="35%">Old Data</th>
                            <th width="35%">New Data</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($newData as $key => $value):

                        $oldVal = $oldData[$key] ?? '';
                        $isChanged = trim((string)$oldVal) !== trim((string)$value);
                    ?>
                        <tr class="<?= $isChanged ? 'table-warning' : '' ?>">
                            <td class="<?= $isChanged ? 'fw-bold' : '' ?>">
                                <?= htmlspecialchars($key) ?>
                            </td>

                            <td class="<?= $isChanged ? 'fw-bold text-danger' : '' ?>">
                                <?= htmlspecialchars($oldVal ?: '-') ?>
                            </td>

                            <td class="<?= $isChanged ? 'fw-bold text-success' : '' ?>">
                                <?= htmlspecialchars($value) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="text-center mt-4">
    
    <a href="approve_update.php?serial_no=<?= urlencode($serial_no) ?>"
       class="btn btn-success px-4 py-2 mt-2">
        ✅ Approve Update
    </a>

    <a href="reject_update.php?serial_no=<?= urlencode($serial_no) ?>"
       class="btn btn-danger px-4 py-2 mt-2">
        ❌ Reject Update
    </a>

    <a href="update_requests.php"
       class="btn btn-primary px-4 py-2 mt-2">
        ⬅ Back
    </a>

</div>


</div>

</body>
</html>
