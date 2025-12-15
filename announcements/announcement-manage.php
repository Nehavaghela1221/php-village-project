<?php require __DIR__ . '/../admin/auth.php'; ?>

<?php
// Timezone
date_default_timezone_set('Asia/Kolkata');

  // 🔐 common admin security
require '../config/db.php';

$currentTime = date("Y-m-d H:i:s");
$data = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcement Manager</title>

<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f7fb;
  padding: 30px;
}

h2 {
  text-align: center;
  margin-bottom: 10px;
}

/* TOP BAR */
.admin-topbar {
  text-align: right;
  margin-bottom: 15px;
}

.logout-btn {
  padding: 8px 16px;
  background: #e53935;
  color: #fff;
  text-decoration: none;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
}

.logout-btn:hover {
  background: #d32f2f;
}

/* FORM */
form {
  max-width: 500px;
  margin: auto;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

input, textarea, button {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
}

textarea {
  resize: vertical;
  min-height: 80px;
}

button {
  background: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  margin-top: 15px;
}

/* LIST */
.announcement-grid {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 40px;
}

/* CARD */
.announcement-card {
  background: #ffffff;
  border-radius: 14px;
  padding: 20px 25px;
  border-left: 6px solid #4CAF50;
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.announcement-card.expired {
  border-left-color: #e53935;
  background: #fff5f5;
}

.announcement-card h3 {
  margin: 0 0 6px;
  font-size: 20px;
}

.announcement-card p {
  font-size: 15px;
  line-height: 1.7;
  color: #444;
  margin-bottom: 10px;
}

.announcement-card small {
  font-size: 13px;
  color: #666;
}

/* ACTIONS */
.actions {
  margin-top: 8px;
  text-align: right;
}

.actions a {
  font-size: 14px;
  margin-left: 12px;
  text-decoration: none;
}

.actions .edit {
  color: #1976d2;
}

.actions .delete {
  color: #e53935;
}
</style>
</head>

<body>

<div class="admin-topbar">
  <a href="http://localhost/devinapura/admin/logout.php
" class="logout-btn">🚪 Logout</a>
</div>

<h2>Add Announcement</h2>

<form method="POST" action="announcement-save.php">
  <input type="text" name="title" placeholder="Title" required>
  <textarea name="description" placeholder="Description" required></textarea>
  <label>Expire Date & Time</label>
  <input type="datetime-local" name="expire_at" required>
  <button type="submit">Submit</button>
</form>

<div class="announcement-grid">

<?php while ($row = $data->fetch_assoc()) {
  $isExpired = (strtotime($currentTime) > strtotime($row['expire_at']));
?>
  <div class="announcement-card <?php echo $isExpired ? 'expired' : ''; ?>"
       data-expire="<?php echo strtotime($row['expire_at']); ?>">

    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

    <small>
      Created: <?php echo date("d M Y H:i", strtotime($row['created_at'])); ?><br>
      Expire: <?php echo date("d M Y H:i", strtotime($row['expire_at'])); ?>
    </small>

    <div class="actions">
      <a class="edit" href="announcement-edit.php?id=<?php echo $row['id']; ?>">✏️ Edit</a>
      |
      <a class="delete"
         href="announcement-delete.php?id=<?php echo $row['id']; ?>"
         onclick="return confirm('Delete this announcement?')">
         🗑 Delete
      </a>
    </div>

  </div>
<?php } ?>

</div>

<script>
function checkExpiry() {
  const now = Math.floor(Date.now() / 1000);

  document.querySelectorAll('.announcement-card').forEach(card => {
    const expireTime = parseInt(card.dataset.expire);
    if (now > expireTime) {
      card.classList.add('expired');
    }
  });
}
checkExpiry();
setInterval(checkExpiry, 10000);
</script>

</body>
</html>
