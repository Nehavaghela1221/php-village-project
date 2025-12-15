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
<html>
<head>
    <title>Compare Update</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
        }
        table{
            width:95%;
            margin:auto;
            border-collapse:collapse;
            background:#fff;
        }
        th,td{
            border:1px solid #ccc;
            padding:10px;
        }
        th{
            background:#222;
            color:#fff;
        }
        .old{
            background:#f8d7da;
        }
        .new{
            background:#d1e7dd;
        }
        .btn{
            padding:10px 16px;
            text-decoration:none;
            color:#fff;
            border-radius:4px;
            margin-right:10px;
        }
        .approve{ background:#198754; }
        .reject{ background:#dc3545; }
        .back{ background:#0d6efd; }
    </style>
</head>
<body>

<h2 style="text-align:center;margin:20px;">
    Compare Member Update (<?= htmlspecialchars($serial_no) ?>)
</h2>

<table>
<tr>
    <th>Field</th>
    <th>Old Data</th>
    <th>New Data</th>
</tr>

<?php foreach ($newData as $key => $value): ?>
<tr>
    <td><?= htmlspecialchars($key) ?></td>
    <td class="old"><?= htmlspecialchars($oldData[$key] ?? '-') ?></td>
    <td class="new"><?= htmlspecialchars($value) ?></td>
</tr>
<?php endforeach; ?>

</table>

<br><br>

<div style="text-align:center;">
    <a class="btn approve" href="approve_update.php?serial_no=<?= $serial_no ?>">
        ✅ Approve Update
    </a>

    <a class="btn reject" href="reject_update.php?serial_no=<?= $serial_no ?>">
        ❌ Reject Update
    </a>

    <a class="btn back" href="update_requests.php">
        ⬅ Back
    </a>
</div>

</body>
</html>
