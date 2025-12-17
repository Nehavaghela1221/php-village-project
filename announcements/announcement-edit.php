<?php
require '../admin/auth.php';
require '../config/db.php';

$id = $_GET['id'] ?? '';

if (!is_numeric($id)) {
  header("Location: announcement-manage.php?error=invalid");
  exit;
}

$stmt = $conn->prepare("SELECT * FROM announcements WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
  header("Location: announcement-manage.php?error=notfound");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Announcement</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="/devinapura/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/devinapura/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="/devinapura/assets/css/main.css" rel="stylesheet">

<style>
.edit-card{
  max-width:600px;
  margin:60px auto;
  border-radius:16px;
  box-shadow:0 15px 40px rgba(0,0,0,.12);
}
</style>
</head>

<body class="bg-light">
<!-- ================= ADMIN NAVBAR ================= -->
<div class="admin-navbar">
  <div class="admin-navbar-inner">
    <h2>📸 Gallery Admin</h2>
    <a href="http://localhost/devinapura/admin/logout.php" class="exit-btn">Exit Admin</a>
  </div>
</div>
<div class="container">

  <div class="card edit-card">
    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">✏️ Edit Announcement</h5>
      <a href="announcement-manage.php" class="btn btn-sm btn-light">⬅ Back</a>
    </div>

    <div class="card-body">
      <form method="POST" action="announcement-update.php">

        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control"
                 value="<?= htmlspecialchars($row['title']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($row['description']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Expire Date & Time</label>
          <input type="datetime-local" name="expire_at" class="form-control"
                 value="<?= date('Y-m-d\TH:i', strtotime($row['expire_at'])) ?>" required>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success px-4">
            ✅ Update Announcement
          </button>
        </div>

      </form>
    </div>
  </div>

</div>

</body>
</html>
