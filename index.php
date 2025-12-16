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
<<<<<<< HEAD
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Village Dashboard</title>

<!-- Vendor CSS -->
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet">

<!-- Main CSS -->
<link href="assets/css/main.css" rel="stylesheet">

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
}
</style>
</head>

<body>

<?php include 'header.php'; ?>

<main class="main">

<!-- ===== HERO ===== -->
<div class="page-title" data-aos="fade">
  <div class="heading d-flex align-items-center justify-content-center text-white"
       style="background:url('assets/img/main-page-img.jpg') center/cover;height:380px;">
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
            <div class="icon-circle"><i class="bi bi-house-heart-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $familyCount ?>"
                data-purecounter-duration="1">
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
            <div class="icon-circle"><i class="bi bi-people-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $memberCount ?>"
                data-purecounter-duration="1">
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
            <div class="icon-circle"><i class="bi bi-megaphone-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $announcementCount ?>"
                data-purecounter-duration="1">
              <?= $announcementCount ?>
            </h4>
            <p class="box-title">Announcements</p>
            <small class="box-sub">Active notices</small>
          </div>
        </a>
      </div>

      <!-- Notifications -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="admin\update_requests.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle"><i class="bi bi-bell-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $notificationCount ?>"
                data-purecounter-duration="1">
              <?= $notificationCount ?>
            </h4>
            <p class="box-title">Alerts</p>
            <small class="box-sub">Total notifications</small>
          </div>
        </a>
      </div>

      <!-- Notifications -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="Members_Directory.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle"><i class="bi bi-bell-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $notificationCount ?>"
                data-purecounter-duration="1">
              <?= $notificationCount ?>
            </h4>
            <p class="box-title">Members Directory</p>
            <small class="box-sub">Total notifications</small>
          </div>
        </a>
      </div>

       <!-- Notifications -->
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <a href="contact.php" class="text-decoration-none">
          <div class="pricing-item">
            <div class="icon-circle"><i class="bi bi-bell-fill"></i></div>
            <h4 class="purecounter"
                data-purecounter-start="0"
                data-purecounter-end="<?= $notificationCount ?>"
                data-purecounter-duration="1">
              <?= $notificationCount ?>
            </h4>
            <p class="box-title">Contacts</p>
            <small class="box-sub">Total notifications</small>
          </div>
        </a>
      </div>

=======
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devinapura | Dashboard</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #FFF8E1; /* village cream */
      color: #4E342E;
    }

    /* ===== HERO ===== */
    .hero {
      position: relative;
      height: 600px;
      background: url("uploads/home.jpg") center top / cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(255, 248, 225, 0.55);
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-content h1 {
      font-size: 42px;
      font-weight: 700;
      color: #6A1B9A;
    }

    .hero-content p {
      font-size: 16px;
      margin-top: 6px;
      color: #4E342E;
    }

    /* ===== DASHBOARD ===== */
    .dashboard-section {
      padding: 60px 0;
    }

    .dashboard-card {
      background: #FFFFFF;
      border-radius: 16px;
      padding: 25px 15px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }

    /* Hover only for desktop */
    @media (hover: hover) {
      .dashboard-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 35px rgba(0,0,0,0.15);
      }
    }

    .icon-circle {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #F0AB0A, #D37810);
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      margin: 0 auto 15px;
    }

    .dashboard-card h4 {
      font-size: 34px;
      font-weight: 700;
      color: #F0AB0A;
      margin-bottom: 4px;
    }

    .dashboard-card span {
      font-size: 14px;
      color: #6D4C41;
    }

    a.dashboard-link {
      text-decoration: none;
      color: inherit;
    }

    /* ===== MOBILE OPTIMIZATION ===== */
    @media (max-width: 768px) {
      .hero {
        height: 420px;
      }

      .hero-content h1 {
        font-size: 30px;
      }

      .dashboard-section {
        padding: 40px 0;
      }

      /* Disable animation on mobile */
      [data-aos] {
        opacity: 1 !important;
        transform: none !important;
      }
    }
  </style>
</head>

<body>

<?php include 'header.php'; ?>

<main>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Village Dashboard</h1>
      <p>Devinapura Digital Village Management System</p>
    </div>
  </section>

  <!-- DASHBOARD -->
  <section class="dashboard-section">
    <div class="container">
      <div class="row g-4">

        <div class="col-6 col-lg-3">
          <a href="family-members.php" class="dashboard-link">
            <div class="dashboard-card">
              <div class="icon-circle"><i class="bi bi-house-fill"></i></div>
              <h4>19</h4>
              <span>Families</span>
            </div>
          </a>
        </div>

        <div class="col-6 col-lg-3">
          <a href="member-list.php" class="dashboard-link">
            <div class="dashboard-card">
              <div class="icon-circle"><i class="bi bi-people-fill"></i></div>
              <h4>96</h4>
              <span>Members</span>
            </div>
          </a>
        </div>

        <div class="col-6 col-lg-3">
          <a href="announcements.php" class="dashboard-link">
            <div class="dashboard-card">
              <div class="icon-circle"><i class="bi bi-megaphone-fill"></i></div>
              <h4>12</h4>
              <span>Announcements</span>
            </div>
          </a>
        </div>

        <div class="col-6 col-lg-3">
          <a href="notifications.php" class="dashboard-link">
            <div class="dashboard-card">
              <div class="icon-circle"><i class="bi bi-bell-fill"></i></div>
              <h4>5</h4>
              <span>Notifications</span>
            </div>
          </a>
        </div>

      </div>
>>>>>>> b2fbef5 (Updated index.php)
    </div>
  </section>

</main>

<?php include 'footer.php'; ?>

<<<<<<< HEAD
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

<!-- MUST BE LAST -->
<script src="assets/js/main.js"></script>

<script>
  AOS.init();
  new PureCounter();
</script>


</body>
</html>
=======
<!-- JS -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<script>
  // Disable AOS on mobile
  if (window.innerWidth > 768) {
    AOS.init({ duration: 700, once: true });
  }
</script>

</body>
</html>
>>>>>>> b2fbef5 (Updated index.php)
