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
<img src="uploads/gallery/images/<?php echo $file; ?>" loading="lazy">
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
