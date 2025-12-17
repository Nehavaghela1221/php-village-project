<?php
date_default_timezone_set('Asia/Kolkata');
require '../config/db.php';

$currentTime = date("Y-m-d H:i:s");

/* ✅ ACTIVE FIRST, THEN EXPIRED */
$data = $conn->query("
  SELECT *,
  (expire_at < '$currentTime') AS is_expired
  FROM announcements
  ORDER BY is_expired ASC, created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements</title>

<style>
/* ===== PAGE BASE ===== */
body{
  background:#FFF8E1;
  font-family:Poppins,system-ui,sans-serif;
  color:#4E342E;

}

h2{margin:20px 0}

/* ===== ADD BUTTON ===== */
.add-btn{
  background:linear-gradient(135deg,#F0AB0A,#D37810);
  color:#fff;
  border:none;
  padding:12px 20px;
  border-radius:30px;
  font-weight:600;
  cursor:pointer;
  margin-top: 25PX
}
.add-btn:hover{transform:translateY(-2px)}

/* ===== LAYOUT ===== */
.page-layout{
  display:grid;
 
  gap:25px;
  
}
@media(max-width:900px){
  .page-layout{grid-template-columns:1fr;}
  
}

/* ===== ANNOUNCEMENT CARD ===== */
.notification-list{
  background:#fff;
  border-radius:18px;
  padding:18px 22px;
  margin-bottom:18px;
  box-shadow:0 8px 28px rgba(0,0,0,.08);
  transition:.25s;
  border-left:6px solid transparent;
  width:100%;
}
.notification-list:hover{transform:translateY(-4px)}
.notification-list.active{border-left-color:#2E7D32}
.notification-list.expired{
  border-left-color:#C62828;
  opacity:.75;
}

/* ===== HEADER ===== */
.announcement-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:10px;
}
.announcement-header h4{
  margin:0;
  font-size:18px;
  font-weight:700;
  color:#6A1B9A;
}

/* Status badge */
.status-text{
  font-size:12px;
  padding:6px 12px;
  border-radius:20px;
  font-weight:600;
}
.notification-list.active .status-text{
  background:#E8F5E9;
  color:#2E7D32;
}
.notification-list.expired .status-text{
  background:#FDECEA;
  color:#C62828;
}

/* Content */
.notification-details p{
  margin:8px 0 12px;
  font-size:14px;
  line-height:1.6;
}
.announcement-meta{
  font-size:12px;
  color:#8D6E63;
}

/* ===== PIN MODAL ===== */
.pin-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.55);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:999;
}
.pin-modal{
  background:#fff;
  padding:25px;
  width:90%;
  max-width:360px;
  border-radius:18px;
  box-shadow:0 12px 40px rgba(0,0,0,.25);
  text-align:center;
}
.pin-modal h3{color:#6A1B9A}
.pin-modal input{
  width:100%;
  padding:12px;
  border-radius:10px;
  border:1px solid #ddd;
  margin:12px 0;
}
.pin-actions{display:flex;gap:10px}
.cancel-btn{
  flex:1;background:#eee;border:none;padding:10px;border-radius:10px;
}
.verify-btn{
  flex:1;background:#4CAF50;color:#fff;border:none;padding:10px;border-radius:10px;
}
.pin-error{color:#C62828;font-size:13px}
/* REMOVE TEMPLATE LEFT STRIP */
.notification-list::before,
.notification-list::after {
  display: none !important;
  content: none !important;
}
.notification-list{
  width:100%;
  background:#fff;
  border-radius:18px;
  padding:20px 24px;
  margin-bottom:20px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  border-left:6px solid transparent;
}

/* ACTIVE */
.notification-list.active{
  border-left-color:#2E7D32; /* GREEN */
}

/* EXPIRED */
.notification-list.expired{
  border-left-color:#D32F2F; /* RED */
  background:#FFF4F4;
  opacity:.9;
}
.notification-list.expired p{
  color:#7F4A4A;
}.page-layout > div:first-child {
  width:100%;
}
/* ===== TOP BAR ===== */
.announcement-topbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:15px;
  padding: 10px;
}

.announcement-topbar h2{
  margin:0;
  font-weight:800;
  color:#6A1B9A;
}

/* ===== MOBILE VIEW ===== */
@media (max-width: 600px){
  .page-layout{
    padding: 10px
  }
  .announcement-topbar{
    flex-direction:column;
    align-items:flex-start;
  }

  .announcement-topbar .add-btn{
    width:100%;
    text-align:center;
  }
  
}

</style>
</head>

<body>

<?php require_once __DIR__ . '/../header.php'; ?>

<div class="announcement-topbar">
  <h2>📢 Village Announcements</h2>
  <button class="add-btn" onclick="openPinModal()">➕ Add Announcement</button>
</div>


<div class="page-layout">

<!-- ===== ANNOUNCEMENTS ===== -->
<div>
<?php while ($row = $data->fetch_assoc()) { ?>
  <div class="notification-list <?php echo $row['is_expired'] ? 'expired' : 'active'; ?>"
       data-expire="<?php echo strtotime($row['expire_at']); ?>">

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
<?php } ?>
</div>

</div>

<!-- ===== PIN MODAL ===== -->
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
function checkExpiry(){
  const now=Math.floor(Date.now()/1000);
  document.querySelectorAll('.notification-list').forEach(card=>{
    const exp=parseInt(card.dataset.expire);
    if(now>exp){
      card.classList.remove('active');
      card.classList.add('expired');
      card.querySelector('.status-text').textContent='❌ Expired';
    }
  });
}
checkExpiry();
setInterval(checkExpiry,10000);

function openPinModal(){
  pinOverlay.style.display="flex";
  adminPin.value="";
  pinError.innerText="";
}
function closePinModal(){pinOverlay.style.display="none"}
function verifyPin(){
  fetch("/devinapura/admin/verify-pin.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:"pin="+encodeURIComponent(adminPin.value)
  }).then(r=>r.text()).then(res=>{
    if(res.trim()==="success") location.href="announcement-manage.php";
    else pinError.innerText="Wrong security code";
  });
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/devinapura/footer.php'; ?>

</body>
</html>
