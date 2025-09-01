<?php 
require_once __DIR__ . '/db.php'; 
require_once __DIR__ . '/includes/header.php'; 

// Fetch categories
$cats = [];
$res = $conn->query("SELECT name FROM categories ORDER BY name ASC");
while ($row = $res->fetch_assoc()) $cats[] = $row['name'];

// Handle filters
$q        = trim($_GET['q'] ?? '');
$city     = trim($_GET['city'] ?? '');
$category = trim($_GET['category'] ?? '');

// Pagination setup
$limit = 6;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$sql = "SELECT SQL_CALC_FOUND_ROWS business_id, name, category, city, state, address, phone, image
        FROM businesses WHERE status='approved'";

$binds = []; $types = '';

if ($q !== '') {
  $sql .= " AND (name LIKE ? OR description LIKE ?)";
  $binds[] = "%$q%"; $binds[] = "%$q%"; $types .= "ss";
}
if ($city !== '') {
  $sql .= " AND (city LIKE ? OR address LIKE ?)";
  $binds[] = "%$city%"; $binds[] = "%$city%"; $types .= "ss";
}
if ($category !== '') {
  $sql .= " AND category = ?";
  $binds[] = $category; $types .= "s";
}

$sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
if (count($binds)) $stmt->bind_param($types, ...$binds);
$stmt->execute();
$result = $stmt->get_result();
$biz = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

// Total rows for pagination
$totalRes = $conn->query("SELECT FOUND_ROWS() as total");
$total = $totalRes->fetch_assoc()['total'] ?? 0;
$pages = ceil($total / $limit);
?>

<section class="py-5 bg-ivory">
  <div class="container">
    <div class="row">
      
      <!-- Sidebar Filters -->
      <aside class="col-lg-3 mb-4">
        <div class="p-4 rounded oldmoney-card shadow-sm">
          <h5 class="fw-bold mb-3 text-deepgreen" style="font-family:'Playfair Display',serif;">Filters</h5>
          <form method="get" action="business-list.php">
            <div class="mb-3">
              <label class="form-label">Search</label>
              <input type="text" name="q" class="oldmoney-input form-control" value="<?php echo e($q); ?>" placeholder="Business name">
            </div>
            <div class="mb-3">
              <label class="form-label">City</label>
              <input type="text" name="city" class="oldmoney-input form-control" value="<?php echo e($city); ?>" placeholder="City">
            </div>
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select name="category" class="oldmoney-select form-select">
                <option value="">All</option>
                <?php foreach ($cats as $c): ?>
                  <option value="<?php echo e($c); ?>" <?php echo $category===$c?'selected':''; ?>>
                    <?php echo e($c); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button class="oldmoney-btn w-100" type="submit">Apply</button>
          </form>
        </div>
      </aside>

      <!-- Business Results -->
      <div class="col-lg-9">
        <h3 class="fw-bold mb-4 text-deepgreen" style="font-family:'Playfair Display',serif;">Browse Businesses</h3>

        <?php if ($total > 0): ?>
          <p class="small text-muted mb-3">
            Showing <strong><?php echo $offset+1; ?></strong> –
            <strong><?php echo min($offset+$limit, $total); ?></strong>
            of <strong><?php echo $total; ?></strong> results
          </p>
        <?php endif; ?>

        <div class="row g-4">
          <?php if (count($biz) === 0): ?>
            <div class="col-12">
              <div class="alert alert-light border">
                No businesses found. Try different filters or 
                <a href="add-business.php" class="text-success">add a business</a>.
              </div>
            </div>
          <?php endif; ?>

          <?php foreach ($biz as $b): 
            $img = !empty($b['image']) ? "/Business_Web/assets/images/uploads/" . e($b['image']) : "/Business_Web/assets/images/placeholder.jpg";
          ?>
            <div class="col-md-6 col-lg-4">
              <div class="card oldmoney-card h-100 shadow-sm border-0">
                <img src="<?php echo e($img); ?>" class="card-img-top" alt="<?php echo e($b['name']); ?>">
                <div class="card-body">
                  <h5 class="card-title fw-bold text-deepgreen"><?php echo e($b['name']); ?></h5>
                  <p class="text-muted small mb-1"><?php echo e($b['category']); ?></p>
                  <p class="small"><?php echo e($b['city']); ?><?php echo $b['state'] ? ', ' . e($b['state']) : ''; ?></p>
                  <p class="mb-0 small"><span class="text-success">☎</span> <?php echo e($b['phone'] ?: 'N/A'); ?></p>
                </div>
                <div class="card-footer bg-white border-0">
                  <a class="btn btn-outline-success w-100 oldmoney-btn" href="business.php?id=<?php echo (int)$b['business_id']; ?>">View details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <!-- Pagination -->
<?php if ($pages > 1): ?>
  <nav class="mt-5">
    <ul class="pagination justify-content-center oldmoney-pagination">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page'=>$page-1])); ?>">&laquo; Prev</a>
        </li>
      <?php endif; ?>

      <?php 
        $range = 2; // how many numbers around current page
        for ($i = max(1, $page - $range); $i <= min($pages, $page + $range); $i++): 
      ?>
        <li class="page-item <?php echo $i==$page?'active':''; ?>">
          <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page'=>$i])); ?>">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $pages): ?>
        <li class="page-item">
          <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page'=>$page+1])); ?>">Next &raquo;</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
<?php endif; ?>

      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<style>

.oldmoney-pagination .page-link {
  font-family: "Playfair Display", serif;
  color: #1c3d2e;
  border: none;
  background: transparent;
  margin: 0 4px;
  padding: 6px 12px;
  transition: color 0.3s, border-bottom 0.3s;
}

.oldmoney-pagination .page-link:hover {
  color: #2f6f4f;
  border-bottom: 2px solid #2f6f4f;
  background: transparent;
}

.oldmoney-pagination .active .page-link {
  color: #2f6f4f;
  font-weight: bold;
  border-bottom: 2px solid #2f6f4f;
  background: transparent;
}

  </style>
