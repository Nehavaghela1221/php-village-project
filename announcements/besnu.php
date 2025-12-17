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
    }/* ===============================
   FIX IMAGE HEIGHT (FINAL)
================================ */

.besnu-img-wrap {
  width: 100%;
  height: 220px;              /* SAME HEIGHT FOR ALL */
  border-radius: 10px;
  overflow: hidden;
  background: #f1f1f1;
  margin-top: 10px;
}

.besnu-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;          /* CROP, NO STRETCH */
  object-position: center;
}

/* Mobile */
@media (max-width: 600px) {
  .besnu-img-wrap {
    height: 260px;
  }
}
/* ===============================
   ACTION UI – BOOTSTRAP STYLE
================================ */

/* View / Download */
.file-actions {
  margin-top: 6px;
  font-size: 13px;
}

.file-actions a {
  color: #198754; /* bootstrap green */
  text-decoration: none;
  font-weight: 500;
  margin-right: 10px;
}

.file-actions a:hover {
  text-decoration: underline;
}

/* WhatsApp Button */
.btn-wa {
  width: 100%;
  margin-top: 12px;
  padding: 10px 0;
  border-radius: 25px;
  font-weight: 600;
  text-align: center;
}

/* Bottom actions */
.card-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 14px;
}

.card-actions .btn {
  font-size: 13px;
  padding: 6px 14px;
}

/* Edit */
.btn-edit {
  background: #e7f1ff;
  color: #0d6efd;
}

/* Delete */
.btn-del {
  background: #fdeaea;
  color: #dc3545;
}
.besnu-img-wrap {
  width: 100%;
  height: 240px;
  border-radius: 12px;
  overflow: hidden;
  background: #f2f2f2;
  display: flex;
  align-items: center;
  justify-content: center;
}

.besnu-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.card h5 {
  font-weight: 600;
  font-size: 17px;
  margin-bottom: 8px;
}

.card p {
  font-size: 13px;
  margin-bottom: 4px;
  color: #555;
}

.card p b {
  color: #000;
}
.file-actions {
  margin-top: 8px;
  font-size: 13px;
  display: flex;
  gap: 14px;
  align-items: center;
}

.file-actions a {
  color: #198754;
  font-weight: 600;
  text-decoration: none;
}

.file-actions a:hover {
  text-decoration: underline;
}
.btn-wa {
  margin-top: 12px;
  width: 100%;
  background: linear-gradient(135deg, #25D366, #1ebe5d);
  color: #fff;
  padding: 10px 0;
  border-radius: 30px;
  font-weight: 600;
  text-align: center;
  display: block;
}
.file-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 8px;
  font-size: 13px;
}

.file-label {
  font-weight: 600;
  color: #555;
}

/* Buttons */
.file-btn {
  padding: 4px 10px;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 600;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: all .2s ease;
}

/* View */
.file-btn.view {
  background: #e7f1ff;
  color: #0d6efd;
}

.file-btn.view:hover {
  background: #d0e2ff;
}

/* Download */
.file-btn.download {
  background: #e9f7ef;
  color: #198754;
}

.file-btn.download:hover {
  background: #d1f2e2;
}

  </style>
</head>

<body>

<div class="container">

<h2>🕊️ Besnu News</h2>

<button class="add-btn" onclick="openAddModal()">➕ Add Besnu Announcement</button>


<h3>📰 Live Besnu Announcements</h3>

<div class="row g-4">
<?php
$data = $conn->query("SELECT * FROM besnu_news ORDER BY created_at DESC");
while ($row = $data->fetch_assoc()) {

  $shareText =
    "🕊️ Besnu Announcement\n\n" .
    "Name: {$row['deceased_name']}\n" .
    "Venue: {$row['venue']}\n" .
    "Date & Time: " . date("d M Y, h:i A", strtotime($row['event_datetime'])) . "\n\n" .
    "From: Devinapura Village";

  $waLink = "https://wa.me/?text=" . urlencode($shareText);
?>
  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
    <div class="card h-100">

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
        <div class="besnu-img-wrap">
          <img src="../uploads/besnu-images/<?php echo htmlspecialchars($row['image']); ?>">
        </div>
      <?php } ?>

    <?php if ($row['brochure']) { ?>
  <div class="file-actions">
    <span class="file-label">📄 Brochure</span>

    <a class="file-btn view" 
       href="../uploads/besnu-brochures/<?php echo htmlspecialchars($row['brochure']); ?>" 
       target="_blank">
      👁 View
    </a>

    <a class="file-btn download" 
       href="../uploads/besnu-brochures/<?php echo htmlspecialchars($row['brochure']); ?>" 
       download>
      ⬇ Download
    </a>
  </div>
<?php } ?>



      <a class="btn-wa" href="<?php echo $waLink; ?>" target="_blank">📤 WhatsApp</a>

      
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
