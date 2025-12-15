<?php
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/../config/db.php';

/* ===============================
   AUTO DELETE EXPIRED RECORDS
   =============================== */
$conn->query("DELETE FROM besnu_news WHERE expires_at < NOW()");

/* ===============================
   DELETE
   =============================== */
if (isset($_POST['delete_id'])) {
  $id = (int)$_POST['delete_id'];
  $conn->query("DELETE FROM besnu_news WHERE id=$id");
}

/* ===============================
   EDIT
   =============================== */
if (isset($_POST['edit_id'])) {
  $stmt = $conn->prepare("
    UPDATE besnu_news
    SET deceased_name=?, posted_by=?, venue=?, event_datetime=?
    WHERE id=?
  ");
  $stmt->bind_param(
    "ssssi",
    $_POST['deceased_name'],
    $_POST['posted_by'],
    $_POST['venue'],
    $_POST['event_datetime'],
    $_POST['edit_id']
  );
  $stmt->execute();
}

/* ===============================
   ADD NEW ANNOUNCEMENT
   =============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['deceased_name'])
    && !isset($_POST['edit_id'])) {

  $eventDT   = $_POST['event_datetime'];
  $expiresAt = date('Y-m-d H:i:s', strtotime($eventDT . ' +1 day'));

  // Image upload
  $image = '';
  if (!empty($_FILES['image']['name'])) {
    $image = time() . '_' . $_FILES['image']['name'];
    move_uploaded_file(
      $_FILES['image']['tmp_name'],
      __DIR__ . "/../uploads/besnu-images/$image"
    );
  }

  // Brochure upload
  $brochure = '';
  if (!empty($_FILES['brochure']['name'])) {
    $brochure = time() . '_' . $_FILES['brochure']['name'];
    move_uploaded_file(
      $_FILES['brochure']['tmp_name'],
      __DIR__ . "/../uploads/besnu-brochures/$brochure"
    );
  }

  $stmt = $conn->prepare("
    INSERT INTO besnu_news
    (deceased_name, posted_by, venue, event_datetime, expires_at, image, brochure)
    VALUES (?,?,?,?,?,?,?)
  ");
  $stmt->bind_param(
    "sssssss",
    $_POST['deceased_name'],
    $_POST['posted_by'],
    $_POST['venue'],
    $eventDT,
    $expiresAt,
    $image,
    $brochure
  );
  $stmt->execute();

  header("Location: besnu.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Besnu Announcements</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php require_once __DIR__ . '/../header.php'; ?>

  <style>
    .container { max-width:900px; margin:auto; padding:20px; }

    .add-btn {
      background:#4CAF50;color:#fff;
      padding:12px 20px;border:none;
      border-radius:8px;font-size:16px;
      cursor:pointer;margin-bottom:20px;
    }

    .card {
      background:#fff;padding:15px;
      border-radius:12px;margin-bottom:15px;
      box-shadow:0 4px 10px rgba(0,0,0,.08);
    }

    .card img {
      max-width:200px;
      border-radius:8px;
      margin-top:10px;
    }

    .btn {
      padding:6px 10px;
      border:none;
      border-radius:6px;
      cursor:pointer;
    }

    .btn-edit { background:#2196F3;color:#fff; }
    .btn-del  { background:#e53935;color:#fff; }

    .btn-wa {
      background:#25D366;
      color:#fff;
      padding:6px 10px;
      border-radius:6px;
      text-decoration:none;
      display:inline-block;
      margin-top:10px;
    }

    /* MODALS */
    .modal {
      display:none;
      position:fixed;
      inset:0;
      background:rgba(0,0,0,.5);
      z-index:999;
    }

    .modal-content {
      background:#fff;
      padding:20px;
      width:90%;
      max-width:500px;
      margin:60px auto;
      border-radius:12px;
      position:relative;
    }

    .modal-content input,
    .modal-content button {
      width:100%;
      padding:10px;
      margin-bottom:10px;
    }

    .close {
      position:absolute;
      right:15px;
      top:10px;
      font-size:24px;
      cursor:pointer;
    }
  </style>
</head>

<body>

<div class="container">

<h2>🕊️ Besnu News</h2>

<button class="add-btn" onclick="openAddModal()">➕ Add Besnu Announcement</button>

<h3>📰 Live Besnu Announcements</h3>

<?php
$data = $conn->query("SELECT * FROM besnu_news ORDER BY created_at DESC");
while ($row = $data->fetch_assoc()) {

$shareText =
  "🕊️ Besnu Announcement\n\n".
  "Name: {$row['deceased_name']}\n".
  "Venue: {$row['venue']}\n".
  "Date & Time: ".date("d M Y, h:i A", strtotime($row['event_datetime']))."\n\n".
  "From: Devinapura Village";

$waLink = "https://wa.me/?text=" . urlencode($shareText);
?>

<div class="card">
  <h3>🕊️ <?php echo htmlspecialchars($row['deceased_name']); ?></h3>

  <p><b>Posted By:</b> <?php echo htmlspecialchars($row['posted_by']); ?></p>
  <p><b>Venue:</b> <?php echo htmlspecialchars($row['venue']); ?></p>

  <p><b>Event:</b>
    <?php echo date("d M Y, h:i A", strtotime($row['event_datetime'])); ?>
  </p>

  <p><b>Posted On:</b>
    <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
  </p>

  <?php if ($row['image']) { ?>
    <img src="../uploads/besnu-images/<?php echo htmlspecialchars($row['image']); ?>">
  <?php } ?>

  <?php if ($row['brochure']) { ?>
    <p>
      <a href="../uploads/besnu-brochures/<?php echo htmlspecialchars($row['brochure']); ?>" target="_blank">📄 View</a> |
      <a href="../uploads/besnu-brochures/<?php echo htmlspecialchars($row['brochure']); ?>" download>⬇ Download</a>
    </p>
  <?php } ?>

  <a class="btn-wa" href="<?php echo $waLink; ?>" target="_blank">📤 WhatsApp</a>

  <br><br>

  <button class="btn btn-edit" onclick="openEditModal(
    <?php echo $row['id']; ?>,
    '<?php echo addslashes($row['deceased_name']); ?>',
    '<?php echo addslashes($row['posted_by']); ?>',
    '<?php echo addslashes($row['venue']); ?>',
    '<?php echo $row['event_datetime']; ?>'
  )">✏ Edit</button>

  <form method="POST" style="display:inline">
    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
    <button class="btn btn-del">🗑 Delete</button>
  </form>
</div>

<?php } ?>

</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAddModal()">&times;</span>
    <h3>Add Besnu Announcement</h3>
    <form method="POST" enctype="multipart/form-data">
      <input name="deceased_name" placeholder="Name of Deceased" required>
      <input name="posted_by" placeholder="Posted By" required>
      <input name="venue" placeholder="Venue" required>
      <input type="datetime-local" name="event_datetime" required>
      <input type="file" name="image" accept="image/*">
      <input type="file" name="brochure" accept="application/pdf">
      <button type="submit">Publish</button>
    </form>
  </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Besnu Announcement</h3>
    <form method="POST">
      <input type="hidden" name="edit_id" id="edit_id">
      <input name="deceased_name" id="edit_deceased" required>
      <input name="posted_by" id="edit_posted" required>
      <input name="venue" id="edit_venue" required>
      <input type="datetime-local" name="event_datetime" id="edit_datetime" required>
      <button type="submit">Update</button>
    </form>
  </div>
</div>

<script>
function openAddModal() {
  document.getElementById('addModal').style.display = 'block';
}
function closeAddModal() {
  document.getElementById('addModal').style.display = 'none';
}
function openEditModal(id, name, posted, venue, dt) {
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_deceased').value = name;
  document.getElementById('edit_posted').value = posted;
  document.getElementById('edit_venue').value = venue;
  document.getElementById('edit_datetime').value = dt.replace(' ', 'T');
  document.getElementById('editModal').style.display = 'block';
}
function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
}
window.onclick = function(e) {
  document.querySelectorAll('.modal').forEach(m=>{
    if (e.target === m) m.style.display='none';
  });
}
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>

</body>
</html>
