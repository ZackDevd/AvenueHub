<?php
require_once 'includes/db.php';
include('includes/header.php');

// Fetch top 3 approved businesses
$query = "SELECT business_id, name, city, state, phone FROM businesses WHERE status='approved' ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($query);
?>

<!-- Hero -->
<section class="hero-lux">
     <div class="container">
          <h1>Discover India's Finest</h1>
          <p>Connecting you with exclusive local businesses, curated for your elevated lifestyle.</p>
     </div>
</section>

<!-- Featured Businesses -->
<section class="py-5 featured-section">
     <div class="container">
          <div class="d-flex justify-content-between align-items-center mb-5">
               <h3 class="fw-bold">Featured Businesses</h3>
               <a href="business.php" class="show-more-link">View All <i class="fas fa-arrow-right fa-sm ms-2"></i></a>
          </div>

          <div class="row g-4">
               <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                         <div class="col-md-4">
                              <div class="business-card">
                                   <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                                   <p><?php echo htmlspecialchars($row['city']); ?><?php echo $row['state'] ? ', ' . htmlspecialchars($row['state']) : ''; ?></p>
                                   <p><i class="fas fa-phone-alt me-2 text-muted"></i> <?php echo htmlspecialchars($row['phone'] ?: 'N/A'); ?></p>
                                   <a href="business.php" class="btn-view view-details-btn" data-id="<?= $business['business_id']; ?>">View Details</a>
                              </div>
                         </div>
                    <?php endwhile; ?>
               <?php else: ?>
                    <div class="col-12 text-center">
                         <p class="text-muted">No businesses found yet. Check back later!</p>
                    </div>
               <?php endif; ?>
          </div>
     </div>
</section>

<!-- Intro -->
<section class="py-5 text-center intro-section">
     <div class="container">
          <h2 class="mb-3">Your Gateway to Exclusive Experiences in India</h2>
          <p class="mx-auto mb-4" style="max-width:800px;">
               Avenue Hub curates the finest establishments in India, offering a seamless connection to unparalleled services and distinguished local businesses.
          </p>
          <a href="register-business.php" class="btn btn-lux-primary btn-lg">List Your Business</a>
     </div>
</section>

<?php include('includes/footer.php'); ?>
