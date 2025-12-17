<?php
require 'config/db.php';

/* ===== DASHBOARD COUNTS (FINAL) ===== */

// Families
$familyCount = $conn->query("
  SELECT COUNT(*) FROM members
")->fetch_row()[0];

// Members
$memberCount = $conn->query("
  SELECT COUNT(*) FROM family_members
")->fetch_row()[0];

// Announcements
$announcementCount = $conn->query("
  SELECT COUNT(*) FROM announcements
")->fetch_row()[0];

// Admin Notifications
$notificationCount = $conn->query("
  SELECT COUNT(*) FROM admin_notifications
")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Contact Search</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>


<style>
/* ===== DASHBOARD CARD UI (FIXED) ===== */
.pricing-item{
  background:#fff;
  border-radius:16px;
  padding:20px 10px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  transition:.35s;
  position:relative;
  overflow:hidden;
}
.pricing-item:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 40px rgba(148,116,71,.15);
}
.pricing-item::after{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,rgba(240,171,10,.25),transparent);
  opacity:0;
  transition:.3s;
}
.pricing-item:hover::after{opacity:1}

.icon-circle{
  width:60px;
  height:60px;
  margin:10px auto;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:26px;
  color:#fff;
  background:linear-gradient(135deg,#f0ab0a,#d37810);
}

.pricing-item h4{
  display:block !important;
  opacity:1 !important;
  visibility:visible !important;
  font-size:42px;
  font-weight:700;
  color:#f0ab0a;
  text-align:center;
  margin:15px 0 5px;
}

.box-title{
  text-align:center;
  font-weight:600;
  color:#4E342E;
}

.box-sub{
  display:block;
  text-align:center;
  font-size:13px;
  color:#8d6e63;
}/* FULL WIDTH HERO */
.hero {
  width: 100vw;
  height: calc(100vh - 80px); /* navbar height */
  background: 
    
    url("assets/img/main-page-img.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bg-family     { background: linear-gradient(135deg,#ffb703,#fb8500); }
.bg-members    { background: linear-gradient(135deg,#4dabf7,#1c7ed6); }
.bg-announce   { background: linear-gradient(135deg,#ff6b6b,#e03131); }
.bg-alert      { background: linear-gradient(135deg,#ffd43b,#fab005); }
.bg-directory  { background: linear-gradient(135deg,#9775fa,#7048e8); }
.bg-contact    { background: linear-gradient(135deg,#51cf66,#2f9e44); }


</style>
</head>

<body>

  <?php include 'header.php'; ?>
<main class="main">

<!-- ===== HERO ===== -->
<div class="page-title hero" data-aos="fade">
  <div class="heading d-flex  align-items-center justify-content-center text-white"
       >
    <div class="text-center">
      <h1>Village Dashboard</h1>
      <p>Devinapura Digital Management System</p>
    </div>
  </div>
</div>

<!-- ===== DASHBOARD CARDS ===== -->
<section class="pricing section">
  <div class="container">
    <div class="row gy-4">

      <!-- Families -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="family-members.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-family">
              <i class="bi bi-house-heart-fill"></i>
            </div>
            <h4 class="purecounter"
                data-purecounter-end="<?= $familyCount ?>">
              <?= $familyCount ?>
            </h4>
            <p class="box-title">Families</p>
            <small class="box-sub">Registered households</small>
          </div>
        </a>
      </div>

      <!-- Members -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="member-list.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-members">
              <i class="bi bi-people-fill"></i>
            </div>
            <h4 class="purecounter"
                data-purecounter-end="<?= $memberCount ?>">
              <?= $memberCount ?>
            </h4>
            <p class="box-title">Members</p>
            <small class="box-sub">Total villagers</small>
          </div>
        </a>
      </div>

      <!-- Announcements -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="announcements.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-announce">
              <i class="bi bi-megaphone-fill"></i>
            </div>
            <h4 class="purecounter"
                data-purecounter-end="<?= $announcementCount ?>">
              <?= $announcementCount ?>
            </h4>
            <p class="box-title">Announcements</p>
            <small class="box-sub">Active notices</small>
          </div>
        </a>
      </div>

      <!-- Alerts -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="admin/update_requests.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-alert">
              <i class="bi bi-bell-fill"></i>
            </div>
            <h4 class="purecounter"
                data-purecounter-end="<?= $notificationCount ?>">
              <?= $notificationCount ?>
            </h4>
            <p class="box-title">Alerts</p>
            <small class="box-sub">Pending requests</small>
          </div>
        </a>
      </div>

      <!-- Members Directory -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="Members_Directory.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-directory">
              <i class="bi bi-journal-text"></i>
            </div>
            <h4>📂</h4>
            <p class="box-title">Directory</p>
            <small class="box-sub">Village contacts</small>
          </div>
        </a>
      </div>

      <!-- Contacts -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="contact.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle bg-contact">
              <i class="bi bi-telephone-fill"></i>
            </div>
            <h4>☎️</h4>
            <p class="box-title">Contacts</p>
            <small class="box-sub">Emergency & help</small>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>






<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

<!-- MUST BE LAST -->
<script src="assets/js/main.js"></script>

<script>
  AOS.init();
  new PureCounter();
</script><?php include 'footer.php'; ?>



</body>
</html>