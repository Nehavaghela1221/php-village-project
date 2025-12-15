<?php
require 'config/db.php';
$data = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - Devinapura</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Poppins&family=Raleway&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Main CSS -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Gallery + Modal CSS -->
  <style>
    /* ===== GALLERY ===== */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 16px;
    }

    .gallery-item {
      position: relative;
      aspect-ratio: 1 / 1;
      border-radius: 14px;
      overflow: hidden;
      background: #f3f3f3;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .gallery-item::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: var(--bg);
      background-size: cover;
      background-position: center;
      filter: blur(18px);
      transform: scale(1.2);
      z-index: 0;
    }

    .gallery-item img,
    .gallery-item video {
      position: relative;
      z-index: 1;
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    /* ===== ADMIN BUTTON BAR ===== */
    .gallery-admin-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 12px;
    }

    #galleryAdminBtn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 22px;
      border: none;
      background: #f0ab0a;
      color: #fff;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    #galleryAdminBtn:hover {
      background: #f0ab0a;
    }

    /* ===== MODAL ===== */
    .gallery-modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.55);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 100000;
    }

    .gallery-modal-box {
      width: 360px;
      padding: 24px;
      border-radius: 18px;
      text-align: center;
      background:  linear-gradient(135deg, #f0ab0a, #d37810ff);
      color: #fff;
      box-shadow: 0 20px 50px rgba(0,0,0,0.35);
      animation: popup 0.3s ease;
    }

    @keyframes popup {
      from { transform: scale(0.85); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .gallery-modal-box input {
      width: 100%;
      padding: 12px;
      margin: 14px 0;
      border-radius: 10px;
      border: none;
      outline: none;
    }

    .gallery-modal-actions {
      display: flex;
      gap: 10px;
    }

    .gallery-modal-actions button {
      flex: 1;
      padding: 10px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: 600;
    }

    #galleryCancelBtn {
      background: #ffffffaa;
    }

    #gallerySubmitBtn {
      background: #222;
      color: #fff;
    }

    #galleryMsg {
      min-height: 20px;
      margin-bottom: 10px;
      font-size: 14px;
    }/* PIN MODAL */
.pin-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.pin-modal {
  background:  linear-gradient(135deg, #f0ab0a, #d37810ff);
  padding: 25px;
  border-radius: 14px;
  width: 410px;
  text-align: center;
  color: #fff;
}

.pin-modal input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: none;
  margin-bottom: 15px;
}

.pin-actions {
  display: flex;
  justify-content: space-between;
}

.cancel-btn {
  background: #e0f2e9;
  color: #333;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
}

.verify-btn {
  background: #222;
  color: #fff;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
}

.pin-error {
  margin-top: 10px;
  color: #ffebee;
  font-size: 13px;
}

  </style>
</head>

<body>

<?php include 'header.php'; ?>

<section class="section">
  <div class="container">

    <!-- ADMIN BUTTON -->
    <div class="gallery-admin-bar">
      <button id="galleryAdminBtn" onclick="openPinModal()">
  <i class="bi bi-gear-fill"></i> Update Gallery
</button>

    </div>

    <!-- GALLERY -->
    <div class="gallery-grid">
      <?php while ($row = $data->fetch_assoc()) {
        $file = htmlspecialchars($row['file_name']);
        $isImage = $row['file_type'] === 'image';
        $bg = $isImage
          ? "uploads/gallery/images/$file"
          : "uploads/gallery/videos/$file";
      ?>
        <div class="gallery-item" style="--bg:url('<?php echo $bg; ?>')">
          <?php if ($isImage) { ?>
            <img src="uploads/gallery/images/<?php echo $file; ?>">
          <?php } else { ?>
            <video controls muted preload="metadata">
              <source src="uploads/gallery/videos/<?php echo $file; ?>">
            </video>
          <?php } ?>
        </div>
      <?php } ?>
    </div>

  </div>
</section>


<!-- PIN MODAL -->
<div id="pinOverlay" class="pin-overlay">
  <div class="pin-modal">
    <h3>🔐 Admin Security</h3>
    <p>Enter admin PIN</p>

    <input type="password" id="adminPin" placeholder="Enter PIN">

    <div class="pin-actions">
      <button class="cancel-btn" onclick="closePinModal()">Cancel</button>
      <button class="verify-btn" onclick="verifyPin()">Verify</button>
    </div>

    <p id="pinError" class="pin-error"></p>
  </div>
</div>
  <?php include 'footer.php'; ?>

<!-- ===== JS ===== -->
<script>
function openPinModal() {
  document.getElementById("pinOverlay").style.display = "flex";
  document.getElementById("adminPin").value = "";
  document.getElementById("pinError").innerText = "";
}

function closePinModal() {
  document.getElementById("pinOverlay").style.display = "none";
}

function verifyPin() {
  fetch("admin/verify-pin.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "pin=" + encodeURIComponent(document.getElementById("adminPin").value)
  })
  .then(r => r.text())
  .then(res => {
    if (res.trim() === "success") {
      window.location.href = "gallery-manage.php";
    } else {
      document.getElementById("pinError").innerText = "Wrong security code";
    }
  });
}
</script>



<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
