<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/header.php';

// Get business ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='container py-5 text-center'><div class='alert alert-danger'>Invalid business.</div></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch business details
$stmt = $conn->prepare("SELECT * FROM businesses WHERE business_id = ? AND status='approved' LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$biz = $result->fetch_assoc();
$stmt->close();

if (!$biz) {
    echo "<div class='container py-5 text-center'><div class='alert alert-warning'>Business not found.</div></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$img = !empty($b['image']) 
            ? "assets/images/uploads/" . htmlspecialchars($b['image']) 
            : "assets/images/placeholder.jpg";
?>

<!-- Business Hero -->
<section class="py-5 hero-oldmoney border-bottom">
  <div class="container text-center">
    <img src="<?php echo e($img); ?>" class="img-fluid rounded shadow-sm mb-4" style="max-height: 350px; object-fit: cover;" alt="<?php echo e($biz['name']); ?>">
    <h1 class="display-5 fw-bold text-deepgreen"><?php echo e($biz['name']); ?></h1>
    <p class="text-muted mb-1"><?php echo e($biz['category']); ?> • <?php echo e($biz['city']); ?><?php echo $biz['state'] ? ', ' . e($biz['state']) : ''; ?></p>
    <small class="text-secondary">Listed on <?php echo date("F j, Y", strtotime($biz['created_at'])); ?></small>
  </div>
</section>

<!-- Business Details -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">

      <!-- Main Info -->
      <div class="col-lg-8">
        <div class="card oldmoney-card border-0 shadow-sm">
          <div class="card-body">
            <h4 class="fw-bold mb-3 text-deepgreen">About</h4>
            <p><?php echo nl2br(e($biz['description'] ?? 'No description provided.')); ?></p>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card oldmoney-card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-bold mb-3 text-deepgreen">Contact Info</h5>
            <p class="mb-2"><strong>📍 Address:</strong><br><?php echo e($biz['address'] ?: 'N/A'); ?></p>
            <p class="mb-2"><strong>☎ Phone:</strong><br><?php echo e($biz['phone'] ?: 'N/A'); ?></p>
            <?php if (!empty($biz['website'])): ?>
              <p class="mb-2"><strong>🌐 Website:</strong><br>
              <a href="<?php echo e($biz['website']); ?>" target="_blank" class="oldmoney-btn d-inline-block text-center">Visit Website</a></p>
            <?php endif; ?>
          </div>
        </div>
        <a href="business-list.php" class="oldmoney-btn w-100 mt-3 text-center">← Back to Listings</a>
      </div>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
