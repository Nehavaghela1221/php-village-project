<?php

require 'admin/auth.php';   // 🔐 protect page
require 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Gallery Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Starter Page - Mentor Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Mentor
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <style>
      body {
        font-family: Poppins, sans-serif;
        background: #f5f7fb;

      }

      /* NAVBAR */
      .admin-navbar {
        background: #fff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        padding: 16px 0;
        margin-bottom: 30px;
      }

      .admin-navbar-inner {
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .exit-btn {
        background: #f0ab0a;
        color: #fff;
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none;
      }

      /* UPLOAD */
      .upload-section {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
      }

      .upload-card {
        background: #fff;
        max-width: 520px;
        width: 100%;
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .1);
      }

      .upload-card select,
      .upload-card input {
        width: 100%;
        padding: 12px;
        margin-bottom: 14px;
        border-radius: 12px;
        border: 1px solid #ddd;
      }

      .upload-card button {
        width: 100%;
        padding: 12px;
        border-radius: 22px;
        border: none;
        background: linear-gradient(135deg, #f0ab0a, #d37810ff);
        color: #fff;
        cursor: pointer;
      }
/* ===== FILE INPUT ONE LINE (DESKTOP) ===== */

.file-row {
  display: flex;
  gap: 14px;
}

/* equal height + clean look */
.file-row select,
.file-row input[type="file"] {
  height: 52px;
  border-radius: 12px;
  padding: 10px 12px;
}

/* make both same width */
.file-row select {
  flex: 1;
}

.file-row input[type="file"] {
  flex: 1;
}

/* 📱 MOBILE VIEW: STACK */
@media (max-width: 768px) {
  .file-row {
    flex-direction: column;
  }
}

      /* GALLERY */
      .gallery-box {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 18px;
      }

      .item {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
      }

      .item img,
      .item video {
        width: 100%;
        height: 160px;
        object-fit: cover;
      }

      .delete-btn {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0a0a0a63;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        cursor: pointer;
      }

      /* MODALS */
      .modal,
      .success-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
      }

      .modal-box,
      .success-box {
        background: linear-gradient(135deg, #f0ab0a, #d37810ff);
        padding: 26px;
        width: 320px;
        border-radius: 20px;
        color: #fff;
        text-align: center;
      }

      .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
      }

      .modal-actions button {
        flex: 1;
        padding: 8px;
        border-radius: 16px;
        border: none;
        cursor: pointer;
      }

      .success-icon {
        font-size: 40px;
        margin-bottom: 8px;
      }
    </style>
  </head>

<body>

  <!-- NAVBAR -->
  <div class="admin-navbar">
    <div class="admin-navbar-inner">
      <h2 style="    color: #f0ab0a;
">📸 Gallery Admin</h2>
      <a href="admin/logout.php" class="exit-btn">Exit Admin</a>
    </div>
  </div>

  <!-- UPLOAD -->
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

      
        <button type="submit">Upload</button>
      </form>
    </div>
  </div>

  <!-- SUCCESS / ERROR MODALS -->
  <div class="success-modal" id="uploadSuccessModal">
    <div class="success-box">
      <div class="success-icon">😊</div>
      <h3>Upload Successful</h3>
      <p>Files added to gallery</p>
    </div>
  </div>

  <div class="success-modal" id="deleteSuccessModal">
    <div class="success-box">
      <div class="success-icon">😊</div>
      <h3>Deleted Successfully</h3>
      <p>File removed from gallery</p>
    </div>
  </div>

  <div class="success-modal" id="uploadErrorModal">
    <div class="success-box" style="background:linear-gradient(135deg,#ff4d4d,#ff2f2f)">
      <div class="success-icon">😟</div>
      <h3>Upload Failed</h3>
      <p id="errorMsg"></p>
    </div>
  </div>

  <!-- GALLERY -->
  <div class="gallery-box">
    <?php
    $result = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
      ?>
      <div class="item">
        <?php if ($row['file_type'] === 'image') { ?>
          <img src="uploads/gallery/images/<?php echo htmlspecialchars($row['file_name']); ?>">
        <?php } else { ?>
          <video controls>
            <source src="uploads/gallery/videos/<?php echo htmlspecialchars($row['file_name']); ?>">
          </video>
        <?php } ?>
        <button class="delete-btn" onclick="openDeleteModal(<?php echo $row['id']; ?>)">Delete</button>
      </div>
    <?php } ?>
  </div>

  <!-- DELETE CONFIRM -->
  <div class="modal" id="deleteModal">
    <div class="modal-box">
      <h3>🗑 Delete File?</h3>
      <p>This action cannot be undone</p>
      <div class="modal-actions">
        <button onclick="closeDeleteModal()">Cancel</button>
        <button onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>

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

    /* POPUPS */
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