<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please login to register your business");
    exit();
}

include('includes/header.php');
include('includes/db.php');

// Fetch categories from DB
$categories = $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");

// Handle business registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['user_id'] ?? null;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $website = trim($_POST['website']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);

    if ($owner_id && $name && $email && $category_id) {
        $stmt = $conn->prepare("INSERT INTO businesses (owner_id, category_id, name, description, address, city, state, phone, email, website, status, is_verified, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 0, NOW())");
        $stmt->bind_param("iissssssss", $owner_id, $category_id, $name, $description, $address, $city, $state, $phone, $email, $website);
        $stmt->execute();

        $success = "Your business has been submitted successfully and is pending approval.";
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<section class="hero-lux">
  <div class="container">
    <h1>Register Your Business</h1>
    <p>Grow your reach and visibility — get listed on <strong>AvenueHub</strong> today.</p>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="mx-auto" style="max-width: 750px;">
      <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center rounded-4"><?php echo $success; ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger text-center rounded-4"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="bg-white rounded-4 shadow-sm p-5">
        <h3 class="text-center mb-4" style="font-family: 'Playfair Display', serif; color: var(--primary-dark);">Business Details</h3>

        <form method="POST" class="row g-4">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Business Name *</label>
            <input type="text" name="name" class="form-control form-control-lg rounded-3" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Category *</label>
            <select name="category_id" class="form-select form-select-lg rounded-3" required>
              <option value="">-- Select Category --</option>
              <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Email *</label>
            <input type="email" name="email" class="form-control form-control-lg rounded-3" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" class="form-control form-control-lg rounded-3">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">City</label>
            <input type="text" name="city" class="form-control form-control-lg rounded-3">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">State</label>
            <input type="text" name="state" class="form-control form-control-lg rounded-3">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" name="address" class="form-control form-control-lg rounded-3">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Website</label>
            <input type="url" name="website" class="form-control form-control-lg rounded-3">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control form-control-lg rounded-3" rows="4"></textarea>
          </div>

          <div class="col-12 text-center pt-3">
            <button type="submit" class="btn btn-lux-primary px-5">Submit Business</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
