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

<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f7fb;
  margin: 0;
  padding: 20px;
}

h2 {
  margin-bottom: 20px;
}

/* ADD BUTTON */
.add-btn {
  padding: 10px 18px;
  background: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}
.add-btn:hover { background: #43a047; }

/* LAYOUT */
.page-layout {
  display: grid;
  grid-template-columns: 3fr 1fr;
  gap: 25px;
  max-width: 1200px;
  margin: auto;
}

/* ANNOUNCEMENTS */
.announcement-grid {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.announcement-card {
  background: #fff;
  border-radius: 14px;
  padding: 20px 25px;
  border-left: 6px solid #4CAF50;
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.announcement-card h3 {
  margin: 0 0 8px;
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

/* ADS */
.ads-panel {
  background: #fff;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
  height: fit-content;
}

.ads-panel h4 { margin-top: 0; }

.ad-box {
  background: #f1f3f9;
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 15px;
  text-align: center;
  font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .page-layout { grid-template-columns: 1fr; }
}

/* PIN MODAL */
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
  background: linear-gradient(135deg, #66d38e, #4CAF50);
  padding: 25px;
  border-radius: 14px;
  width: 320px;
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
/* ACTIVE ANNOUNCEMENT */
.announcement-card.active {
  border-left: 6px solid #4CAF50;
}

/* EXPIRED ANNOUNCEMENT */
.announcement-card.expired {
  border-left: 6px solid #e53935;
  background: #fff5f5;
}

</style>
</head>

<body>

<div style="text-align:right; margin-bottom:15px;">
  <button class="add-btn" onclick="openPinModal()">➕ Add Announcement</button>
</div>

<h2>📢 Village Announcements</h2>

<div class="page-layout">

 <div class="announcement-grid">
<?php while ($row = $data->fetch_assoc()) { ?>

  <div class="announcement-card <?php echo $row['is_expired'] ? 'expired' : 'active'; ?>"
       data-expire="<?php echo strtotime($row['expire_at']); ?>">

    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

    <small>
  Posted on: <?php echo date("d M Y", strtotime($row['created_at'])); ?><br>
  <span class="status-text">
    <?php echo $row['is_expired'] ? '❌ Expired' : '✅ Active'; ?>
  </span>
</small>

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


</body>
</html>
