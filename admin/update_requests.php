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
<html>
<head>
    <title>Pending Update Requests</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
        }
        table{
            width:90%;
            margin:auto;
            border-collapse:collapse;
            background:#fff;
        }
        th,td{
            padding:12px;
            border:1px solid #ccc;
            text-align:left;
        }
        th{
            background:#222;
            color:#fff;
        }
        a{
            text-decoration:none;
            padding:6px 10px;
            background:#0d6efd;
            color:#fff;
            border-radius:4px;
        }
        .empty{
            text-align:center;
            padding:30px;
            color:#777;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;margin:20px;">Pending Member Update Requests</h2>

<table>
<tr>
    <th>Serial No</th>
    <th>Member Name</th>
    <th>Requested On</th>
    <th>Action</th>
</tr>

<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['serial_no']) ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['updated_at']) ?></td>
            <td>
                <a href="view_update.php?serial_no=<?= $row['serial_no'] ?>">
                    View & Compare
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="empty">
            No pending update requests
        </td>
    </tr>
<?php endif; ?>

</table>

</body>
</html>
