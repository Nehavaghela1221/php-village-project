<?php
date_default_timezone_set('Asia/Kolkata');
require '../config/db.php';

$currentTime = date("Y-m-d H:i:s");

$data = $conn->query("
  SELECT *,
  (expire_at < '$currentTime') AS is_expired
  FROM announcements
  ORDER BY created_at DESC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements</title>



</head>

<body>
<?php require_once __DIR__ . '/../header.php'; ?>
<div style="text-align:right; margin-bottom:15px;">
  <button class="add-btn" onclick="openPinModal()">➕ Add Announcement</button>
</div>

<h2 style="font-weight:800; color:#6A1B9A;">📢 Village Announcements</h2>

<div class="page-layout">

 <div class="page-layout">

<?php while ($row = $data->fetch_assoc()) { ?>

  <div class="notification-list <?php echo $row['is_expired'] ? 'expired' : 'active'; ?>"
       data-expire="<?php echo strtotime($row['expire_at']); ?>">

    <div class="notification-content">

   

      <div class="notification-details">

  <div class="announcement-header">
    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
    <span class="status-text">
      <?php echo $row['is_expired'] ? '❌ Expired' : '✅ Active'; ?>
    </span>
  </div>

  <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

  <div class="announcement-meta">
    📅 <?php echo date("d M Y", strtotime($row['created_at'])); ?>
  </div>

</div>


    </div>
  </div>

<?php } ?>

</div>


  <div class="ads-panel">
    <h4>📌 Notices / Ads</h4>
    <div class="ad-box">🏠 House for Rent<br>Contact: 9XXXXXXXXX</div>
    <div class="ad-box">🎉 Wedding Invitation<br>Date: 25 Dec 2025</div>
    <div class="ad-box">📢 Your Ad Here</div>
  </div>

</div>

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



<script>
function checkExpiry() {
  const now = Math.floor(Date.now() / 1000);

  document.querySelectorAll('.announcement-card').forEach(card => {
    const expireTime = parseInt(card.dataset.expire);
    if (!expireTime) return;

    const statusText = card.querySelector('.status-text');

    if (now > expireTime) {
      card.classList.remove('active');
      card.classList.add('expired');
      if (statusText) statusText.textContent = '❌ Expired';
    } else {
      card.classList.remove('expired');
      card.classList.add('active');
      if (statusText) statusText.textContent = '✅ Active';
    }
  });
}

checkExpiry();
setInterval(checkExpiry, 10000); // every 10 sec

function openPinModal() {
  document.getElementById("pinOverlay").style.display = "flex";
  document.getElementById("adminPin").value = "";
  document.getElementById("pinError").innerText = "";
}

function closePinModal() {
  document.getElementById("pinOverlay").style.display = "none";
}

function verifyPin() {
  fetch("/devinapura/admin/verify-pin.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "pin=" + encodeURIComponent(document.getElementById("adminPin").value)
  })
  .then(r => r.text())
  .then(res => {
    if (res.trim() === "success") {
      window.location.href = "announcement-manage.php";
    } else {
      document.getElementById("pinError").innerText = "Wrong security code";
    }
  });
}
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>
