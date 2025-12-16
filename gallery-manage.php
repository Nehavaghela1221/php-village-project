<?php
require 'admin/auth.php';
require 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery Admin | Devinapura</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- ===== ADVANCED POPUP UI ===== -->
  <style>
    /* OVERLAY */
    .modal, .success-modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.55);
      backdrop-filter: blur(6px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    /* POPUP BOX */
    .modal-box, .success-box {
      background: #fff;
      width: 360px;
      max-width: 92%;
      padding: 26px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 25px 60px rgba(0,0,0,.25);
      animation: popup .25s ease;
    }

    @keyframes popup {
      from { opacity: 0; transform: scale(.9); }
      to   { opacity: 1; transform: scale(1); }
    }

    /* ICON */
    .popup-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 14px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }

    .icon-danger { background: #ffecec; color: #e53935; }
    .icon-success { background: #e8f5e9; color: #2e7d32; }
    .icon-error { background: #ffebee; color: #c62828; }

    /* TEXT */
    .modal-box h3, .success-box h3 {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 6px;
      color: #333;
    }

    .modal-box p, .success-box p {
      font-size: 14px;
      color: #666;
    }

    /* ACTIONS */
    .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 18px;
    }

    .modal-actions button {
      flex: 1;
      padding: 10px;
      border-radius: 14px;
      border: none;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-cancel {
      background: #f3f4f6;
      color: #333;
    }

    .btn-delete {
      background: #e53935;
      color: #fff;
      font-weight: 600;
    }

    .btn-delete:hover { background: #d32f2f; }
  </style>
</head>

<body>

<!-- ================= ADMIN NAVBAR ================= -->
<div class="admin-navbar">
  <div class="admin-navbar-inner">
    <h2>📸 Gallery Admin</h2>
    <a href="admin/logout.php" class="exit-btn">Exit Admin</a>
  </div>
</div>

<!-- ================= UPLOAD ================= -->
<div class="upload-section">
  <div class="upload-card">
    <form action="upload-gallery.php" method="POST" enctype="multipart/form-data">
      <div class="file-row">
        <select name="file_type" required>
          <option value="">Select File Type</option>
          <option value="image">Image</option>
          <option value="video">Video</option>
        </select>
        <input type="file" name="file[]" multiple required>
      </div>
      <button type="submit">Upload Files</button>
    </form>
  </div>
</div>

<!-- ================= GALLERY ================= -->
<div class="gallery-box">
<?php
$res = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
while ($row = $res->fetch_assoc()) {
  $file = htmlspecialchars($row['file_name']);
  $id = (int)$row['id'];
?>
  <div class="item">
    <?php if ($row['file_type'] === 'image') { ?>
      <img src="uploads/gallery/images/<?php echo $file; ?>" loading="lazy">
    <?php } else { ?>
      <video controls preload="metadata">
        <source src="uploads/gallery/videos/<?php echo $file; ?>">
      </video>
    <?php } ?>
    <button class="delete-btn" onclick="openDeleteModal(<?php echo $id; ?>)">Delete</button>
  </div>
<?php } ?>
</div>

<!-- ================= DELETE POPUP ================= -->
<div class="modal" id="deleteModal">
  <div class="modal-box">
    <div class="popup-icon icon-danger">
      <i class="bi bi-trash3"></i>
    </div>
    <h3>Delete this file?</h3>
    <p>This action cannot be undone.</p>

    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
      <button class="btn-delete" onclick="confirmDelete()">Delete</button>
    </div>
  </div>
</div>

<!-- ================= SUCCESS ================= -->
<div class="success-modal" id="uploadSuccessModal">
  <div class="success-box">
    <div class="popup-icon icon-success">
      <i class="bi bi-check-circle"></i>
    </div>
    <h3>Upload Successful</h3>
    <p>Files added to gallery</p>
  </div>
</div>

<div class="success-modal" id="deleteSuccessModal">
  <div class="success-box">
    <div class="popup-icon icon-success">
      <i class="bi bi-check-circle"></i>
    </div>
    <h3>Deleted Successfully</h3>
    <p>File removed from gallery</p>
  </div>
</div>

<div class="success-modal" id="uploadErrorModal">
  <div class="success-box">
    <div class="popup-icon icon-error">
      <i class="bi bi-x-circle"></i>
    </div>
    <h3>Upload Failed</h3>
    <p id="errorMsg"></p>
  </div>
</div>

<?php include 'footer.php'; ?>

<!-- ================= JS ================= -->
<script>
let deleteId = null;

function openDeleteModal(id) {
  deleteId = id;
  document.getElementById("deleteModal").style.display = "flex";
}

function closeDeleteModal() {
  document.getElementById("deleteModal").style.display = "none";
}

function confirmDelete() {
  window.location.href = "delete-gallery.php?id=" + deleteId;
}

/* SUCCESS / ERROR */
const params = new URLSearchParams(window.location.search);

if (params.get("upload_success") === "1") showModal("uploadSuccessModal");
if (params.get("delete_success") === "1") showModal("deleteSuccessModal");

if (params.has("upload_error")) {
  document.getElementById("errorMsg").innerText =
    decodeURIComponent(params.get("upload_error"));
  showModal("uploadErrorModal");
}

function showModal(id) {
  const modal = document.getElementById(id);
  modal.style.display = "flex";
  setTimeout(() => {
    modal.style.display = "none";
    history.replaceState({}, document.title, "gallery-manage.php");
  }, 1800);
}
</script>

</body>
</html>
