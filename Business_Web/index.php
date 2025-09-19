<?php 
require_once __DIR__ . '/db.php'; 
require_once __DIR__ . '/includes/header.php'; 

// -----------------------------
// Fetch categories
// -----------------------------
$cats = [];
if ($catRes = $conn->query("SELECT name FROM categories ORDER BY name ASC")) {
    while ($row = $catRes->fetch_assoc()) {
        $cats[] = $row['name'];
    }
    $catRes->free();
}

// -----------------------------
// Handle search filters
// -----------------------------
$q        = trim($_GET['q'] ?? '');
$city     = trim($_GET['city'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT business_id, name, category, city, state, address, phone, image, created_at
        FROM businesses
        WHERE status = 'approved'";

$binds = [];
$types = "";

// Keyword search
if ($q !== '') {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $binds[] = "%{$q}%"; 
    $binds[] = "%{$q}%"; 
    $types  .= "ss";
}

// City filter
if ($city !== '') {
    $sql .= " AND (city LIKE ? OR address LIKE ?)";
    $binds[] = "%{$city}%"; 
    $binds[] = "%{$city}%"; 
    $types  .= "ss";
}

// Category filter
if ($category !== '') {
    $sql .= " AND category = ?";
    $binds[] = $category; 
    $types  .= "s";
}

$sql .= " ORDER BY created_at DESC LIMIT 9";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query prepare failed: " . $conn->error);
}

if (count($binds)) {
    $stmt->bind_param($types, ...$binds);
}
$stmt->execute();
$result = $stmt->get_result();
$biz = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();
?>

<!-- Hero Section -->
<section class="hero-oldmoney d-flex align-items-center text-center py-5" style="background: linear-gradient(135deg,#f0f5f1,#ffffff);">
  <div class="container">
    <h1 class="display-4 fw-bold text-deepgreen mb-3" style="font-family:'Playfair Display', serif;">Avenue Hub</h1>
    <p class="lead mb-4 text-muted">Connecting you with trusted local businesses with elegance and tradition.</p>

    <!-- Search Bar -->
    <form class="row g-3 justify-content-center mt-4" method="get" action="index.php">
      <div class="col-12 col-md-4">
        <input type="text" name="q" class="form-control form-control-lg oldmoney-input" placeholder="Search by name..." 
               value="<?php echo htmlspecialchars($q); ?>">
      </div>
      <div class="col-6 col-md-3">
        <input type="text" name="city" class="form-control form-control-lg oldmoney-input" placeholder="City" 
               value="<?php echo htmlspecialchars($city); ?>">
      </div>
      <div class="col-6 col-md-3">
        <select name="category" class="form-select form-select-lg oldmoney-select">
          <option value="">All Categories</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $category === $c ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($c); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-2 d-grid">
        <button class="btn btn-success btn-lg oldmoney-btn">Search</button>
      </div>
    </form>
  </div>
</section>

<!-- Top Businesses Section -->
<section class="py-5" style="background: linear-gradient(135deg,#f0f5f1,#ffffff);">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold text-deepgreen">Top Businesses</h3>
      <a href="business-list.php" class="text-success fw-semibold">Show More →</a>
    </div>

    <div class="row g-4">
      <?php if (count($biz) === 0): ?>
        <div class="col-12">
          <div class="alert alert-light border">
            No businesses found. Try searching or 
            <a href="add-business.php" class="text-success">add one</a>.
          </div>
        </div>
      <?php endif; ?>

      <?php foreach ($biz as $b): 
        // Display uploaded image or placeholder
        $img = !empty($b['image']) 
            ? "assets/images/uploads/" . htmlspecialchars($b['image']) 
            : "assets/images/placeholder.jpg";
      ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100 oldmoney-card" 
               style="border-radius:15px; transition:0.3s; background-color:#fffef7;">
            <img src="<?php echo $img; ?>" class="card-img-top" 
                 alt="<?php echo htmlspecialchars($b['name']); ?>" 
                 style="border-radius:15px 15px 0 0;">
            <div class="card-body">
              <h5 class="card-title fw-bold"><?php echo htmlspecialchars($b['name']); ?></h5>
              <p class="text-muted small mb-1">
                <?php echo htmlspecialchars($b['city']); ?>
                <?php echo $b['state'] ? ', ' . htmlspecialchars($b['state']) : ''; ?>
              </p>
              <p class="mb-0 small"><span class="text-success">☎</span> 
                <?php echo htmlspecialchars($b['phone'] ?: 'N/A'); ?>
              </p>
            </div>
            <div class="card-footer bg-white border-0">
              <a class="btn btn-outline-success w-100 oldmoney-btn" 
                 href="business.php?id=<?php echo (int)$b['business_id']; ?>">View details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Introduction Section -->
<section class="py-5 text-center" style="background: linear-gradient(135deg,#f0f5f1,#ffffff); margin-bottom:30px;">
  <div class="container">
    <h2 class="fw-bold text-deepgreen mb-3">Why Avenue Hub?</h2>
    <p class="text-muted mx-auto mb-4" style="max-width:700px;">
      Avenue Hub is dedicated to connecting communities with trusted local businesses. 
      Our platform combines <strong>classic elegance</strong> with modern accessibility, 
      making it effortless for customers and business owners alike.
    </p>
    <a href="add-business.php" class="btn btn-outline-success btn-lg oldmoney-btn">List Your Business</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<style>
.oldmoney-navbar { position: sticky; top: 0; z-index: 999; background-color:#f8f8f5; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.oldmoney-card { border:1px solid #e3e0d7; }
.oldmoney-btn { border-radius:50px; font-weight:500; }
.text-deepgreen { color:#1f3b2c !important; }
</style>
