<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg lux-navbar">
     <div class="container">
          <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
               <span style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.6rem;color:var(--primary-dark);">
                    Avenue<span style="color:var(--secondary-dark);">Hub</span>
               </span>
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
               <span class="navbar-toggler-icon"></span>
          </button>

          <div id="navMain" class="collapse navbar-collapse">
               <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="business.php">Business</a></li>
                    <li class="nav-item"><a class="nav-link" href="feature.php">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
               </ul>

               <!-- Dynamic Auth Buttons -->
               <div class="d-flex ms-lg-4 mt-3 mt-lg-0">
                    <?php if (isset($_SESSION['user_id'])): ?>
                         <a class="btn btn-lux-outline me-2" href="profile.php">Profile</a>
                         <a class="btn btn-lux-logout" href="logout.php">Logout</a>
                    <?php else: ?>
                         <a class="btn btn-lux-primary" href="register.php">Register</a>
                    <?php endif; ?>
               </div>
          </div>
     </div>
</nav>
