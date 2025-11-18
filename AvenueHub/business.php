<?php
include('includes/header.php');
include('includes/db.php');

// Fetch approved businesses
$query = "SELECT * FROM businesses WHERE status='approved'";
$result = $conn->query($query);
?>

<section class="hero-lux text-center">
  <div class="container">
    <h1>Explore Verified Businesses</h1>
    <p>Handpicked and trusted establishments from across India.</p>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4" id="business-grid">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($business = $result->fetch_assoc()): ?>
          <div class="col-md-4">
            <div class="business-card">
              <h5><?= htmlspecialchars($business['name']); ?></h5>
              <p><?= htmlspecialchars($business['city']); ?>, <?= htmlspecialchars($business['state']); ?></p>
              <p><i class="fas fa-phone-alt me-2 text-muted"></i><?= htmlspecialchars($business['phone']); ?></p>

              <!-- NOTE: class "view-details-btn" and data-id must exist -->
              <a href="#" class="btn-view view-details-btn" data-id="<?= $business['business_id']; ?>">View Details</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">No businesses found yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal (only one in page) -->
<div id="businessModal" class="modal-overlay" aria-hidden="true" role="dialog">
  <div class="modal-box" role="document">
    <button class="modal-close" aria-label="Close modal">&times;</button>
    <div id="modal-content" style="min-height:60px;">
      <!-- content injected here -->
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('businessModal');
  const modalContent = document.getElementById('modal-content');
  const closeBtn = document.querySelector('.modal-close');

  // open modal with HTML
  function openModal(html) {
    modalContent.innerHTML = html;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
  }

  function closeModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modalContent.innerHTML = '';
  }

  // event delegation for view-details-btn clicks
  document.body.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-details-btn');
    if (!btn) return;

    e.preventDefault();
    const id = btn.dataset.id;
    if (!id) {
      console.error('business id not found on button');
      return;
    }

    openModal('<p>Loading...</p>');

    // ensure correct path if get_business.php is in different folder
    const url = 'get-business.php?id=' + encodeURIComponent(id);

    fetch(url, { method: 'GET' })
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok: ' + response.status);
        return response.text();
      })
      .then(html => {
        openModal(html);
      })
      .catch(err => {
        console.error('Fetch error:', err);
        openModal('<p class="text-danger">Could not load business details. Try again later.</p>');
      });
  });

  // close handlers
  closeBtn && closeBtn.addEventListener('click', closeModal);

  window.addEventListener('click', function (e) {
    if (e.target === modal) closeModal();
  });

  // optional: ESC key to close
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
  });
});
</script>
