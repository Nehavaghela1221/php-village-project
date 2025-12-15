<?php
require '../admin/auth.php';   // 🔐 common admin protection
require '../config/db.php';

$id = $_GET['id'] ?? '';

if (!is_numeric($id)) {
    header("Location: announcement-manage.php?error=Invalid request");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

if (!$row) {
    header("Location: announcement-manage.php?error=Not found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Announcement</title>
</head>
<body>

<h2>Edit Announcement</h2>

<form method="POST" action="announcement-update.php">

  <input type="hidden" name="id"
         value="<?php echo htmlspecialchars($row['id']); ?>">

  <input type="text" name="title"
         value="<?php echo htmlspecialchars($row['title']); ?>"
         required>

  <textarea name="description" required><?php
    echo htmlspecialchars($row['description']);
  ?></textarea>

  <label>Expire Date & Time</label>
  <input type="datetime-local" name="expire_at"
    value="<?php echo date('Y-m-d\TH:i', strtotime($row['expire_at'])); ?>"
    required>

  <button type="submit">Update</button>
</form>

</body>
</html>
