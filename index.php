<!DOCTYPE html>
<html lang="en">

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
</head>
<style>
  /* ===== SLIDING SQUARE BORDER EFFECT ===== */

.pricing-item {
  position: relative;
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* pseudo borders */
.pricing-item::before,
.pricing-item::after {
  content: "";
  position: absolute;
  width: 0;
  height: 0;
  border: 2px solid transparent;
  transition: all 0.4s ease;
}

/* top-left → right & down */
.pricing-item::before {
  top: 0;
  left: 0;
}

/* bottom-right → left & up */
.pricing-item::after {
  bottom: 0;
  right: 0;
}

/* hover animation */
.pricing-item:hover::before {
  width: 100%;
  height: 100%;
  border-top-color: #d37810ff;
  border-right-color: #d37810ff;
}

.pricing-item:hover::after {
  width: 100%;
  height: 100%;
  border-bottom-color: #f0ab0a;
  border-left-color: #f0ab0a;
}

/* lift on hover */
.pricing-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(0,0,0,0.15);
}

  .icon-circle {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #f0ab0a, #d37810ff) ;
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  margin: 20px auto 10px;
}
/* ===== ADVANCED DASHBOARD BOX UI ===== */

.pricing-item {
  background: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(194, 13, 13, 0.08);
  transition: all 0.35s ease;
  position: relative;
}

/* Top gradient bar */
.pricing-item h3 {
  background: linear-gradient(135deg, #f0ab0a, #d37810ff);
  color: #fff;
  margin: 0;  
  padding: 16px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
}

/* Number styling */
.pricing-item h4 {
  font-size: 42px;
  font-weight: 700;
  color: #f0ab0a;
  margin: 22px 0;
  text-align: center;
}

/* Hover effect */
.pricing-item:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 40px rgba(148, 116, 71, 0.15);
}

/* Icon inside title */
.pricing-item h3 i {
  margin-right: 6px;
}

/* Link reset */
section#pricing a {
  color: inherit;
}

/* Mobile spacing */
@media (max-width: 768px) {
  .pricing-item h4 {
    font-size: 34px;
  }
   /* .container   {
    margin-bottom: 100px
   } */
}

</style>
<body class="starter-page-page">

  <?php include 'header.php'; ?>

  <main class="main">

  <div class="page-title" data-aos="fade">
  <div class="heading" style="background-image:url('uploads/carsoul1.png');height: 420px;
  background-size: cover;        /* no repeat */
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: #fff;
  position: relative;
  text-align: center;  
 
  ">
    <div class="container">

     <div id="dashboardCarousel"
     class="carousel slide bg-carousel"
     data-bs-ride="carousel"
     data-bs-interval="3500">

  <div class="carousel-inner text-center" >

    <!-- Slide 1 -->
    <div class="carousel-item active bg-slide"
         >
      <div class="overlay"></div>
      <h1>Dashboard</h1>
      <p class="mb-0">Welcome to Devinapura Village Digital Dashboard.</p>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item bg-slide"
         style="background-image:url('assets/img/carousel/slide2.jpg');">
      <div class="overlay"></div>
      <h1>Family Records</h1>
      <p class="mb-0">Access complete family and member information easily.</p>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item bg-slide"
         style="background-image:url('assets/img/carousel/slide3.jpg');">
      <div class="overlay"></div>
      <h1>Announcements</h1>
      <p class="mb-0">Stay updated with village announcements and notices.</p>
    </div>

    <!-- Slide 4 -->
    <div class="carousel-item bg-slide"
         style="background-image:url('assets/img/carousel/slide4.jpg');">
      <div class="overlay"></div>
      <h1>Notifications</h1>
      <p class="mb-0">Never miss important village alerts and updates.</p>
    </div>

  </div>
</div>


    </div>
  </div>
</div>


   <!-- Pricing Section -->
<section id="pricing" class="pricing section">
  <div class="container">
    <div class="row gy-3">

      <!-- Family Members -->
      <a href="family-members.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
          <div class="icon-circle">
            <i class="bi bi-people-fill"></i>
          </div>

          <h4>19</h4>

          <p class="text-muted mb-0">Family Members</p>
        </div>


      </a>
      <!-- End -->

      <!-- Member List -->
      <a href="members_list.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
          <div class="icon-circle">
            <i class="bi bi-people-fill"></i>
          </div>

          <h4>19</h4>

          <p class="text-muted mb-0"> Members List</p>
        </div>

      </a>
      <!-- End -->

      <!-- Announcements -->
      <a href="announcements.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
         <div class="icon-circle"> <i class="bi bi-megaphone-fill"></i></div>

          <h4>19</h4>
           <p class="text-muted mb-0">Annocuments</p>
        </div>

      </a>
      <!-- End -->

      <!-- Notifications -->
      <a href="notifications.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
          <div class="icon-circle"> <i class="bi bi-bell-fill"></i></div>

          <h4>19</h4>
           <p class="text-muted mb-0">Notifications</p>
        </div>

      </a>

       <!-- Notifications -->
      <a href="contact.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
          <div class="icon-circle"> <i class="bi bi-bell-fill"></i></div>

          <h4>19</h4>
           <p class="text-muted mb-0">Contact Us</p>
        </div>

      </a>

      <a href="Members_Directory.php"
         class="col-6 col-xl-3 col-lg-6 text-decoration-none"
         data-aos="fade-up"
         data-aos-delay="200">

        <div class="pricing-item featured">
          <div class="icon-circle"> <i class="bi bi-bell-fill"></i></div>

          <h4>19</h4>
           <p class="text-muted mb-0">Members Directory</p>
        </div>

      </a>

      
      <!-- End -->

    </div>
  </div>
</section>


  </main>


    <?php include 'footer.php'; ?>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>