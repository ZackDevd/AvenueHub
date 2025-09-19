<?php require_once 'includes/header.php'; ?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      
      <!-- Left: Contact Info -->
      <div class="col-md-5 mb-4">
        <h1 class="fw-bold text-deepgreen mb-3" style="font-family:'Playfair Display', serif;">Contact Us</h1>
        <p class="lead text-muted">We’d love to hear from you. Whether you’re a business looking to get listed, or a customer searching for trusted services, our team is here to help.</p>

        <ul class="list-unstyled mt-4">
          <li class="mb-3">
            <i class="fa-solid fa-location-dot text-success me-2"></i>
            <span>123 Avenue Street, Mumbai, India</span>
          </li>
          <li class="mb-3">
            <i class="fa-solid fa-phone text-success me-2"></i>
            <a href="tel:+911234567890" class="text-decoration-none text-dark">+91 12345 67890</a>
          </li>
          <li class="mb-3">
            <i class="fa-solid fa-envelope text-success me-2"></i>
            <a href="mailto:info@avenuehub.com" class="text-decoration-none text-dark">info@avenuehub.com</a>
          </li>
          <li class="mb-3">
            <i class="fa-solid fa-globe text-success me-2"></i>
            <a href="https://avenuehub.com" target="_blank" rel="noopener" class="text-decoration-none text-dark">www.avenuehub.com</a>
          </li>
        </ul>

        <div class="mt-4">
          <a href="https://www.facebook.com" target="_blank" class="me-3 text-success"><i class="fa-brands fa-facebook fa-lg"></i></a>
          <a href="https://www.twitter.com" target="_blank" class="me-3 text-success"><i class="fa-brands fa-twitter fa-lg"></i></a>
          <a href="https://www.linkedin.com" target="_blank" class="me-3 text-success"><i class="fa-brands fa-linkedin fa-lg"></i></a>
          <a href="https://www.instagram.com" target="_blank" class="text-success"><i class="fa-brands fa-instagram fa-lg"></i></a>
        </div>
      </div>

      <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>


      <!-- Right: Contact Form -->
      <div class="col-md-7">
        <div class="card shadow-sm border-0 p-4 oldmoney-card">
          <h3 class="fw-bold mb-3 text-deepgreen">Send Us a Message</h3>

          <form action="send-message.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control oldmoney-input" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control oldmoney-input" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="tel" name="phone" class="form-control oldmoney-input">
            </div>

            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="message" rows="5" class="form-control oldmoney-input" required></textarea>
            </div>

            <button type="submit" class="btn oldmoney-btn w-100">Send Message</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Google Map Embed -->
<section class="py-0">
  <div class="container-fluid px-0">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18..." width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
