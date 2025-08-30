<?php
require_once 'db.php';
require_once 'includes/header.php';

// Only admins allowed
if (empty($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

// Tab selection
$tab = $_GET['tab'] ?? 'dashboard';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Approve business
    if (isset($_POST['approve_business'])) {
        $stmt = $conn->prepare("UPDATE businesses SET status='approved' WHERE business_id=?");
        $stmt->bind_param("i", $_POST['approve_business']);
        $stmt->execute();
        $stmt->close();
    }

    // Delete business
    if (isset($_POST['delete_business'])) {
        $stmt = $conn->prepare("DELETE FROM businesses WHERE business_id=?");
        $stmt->bind_param("i", $_POST['delete_business']);
        $stmt->execute();
        $stmt->close();
    }

    // Delete user (non-admin only)
    if (isset($_POST['delete_user'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND is_admin=0");
        $stmt->bind_param("i", $_POST['delete_user']);
        $stmt->execute();
        $stmt->close();
    }

    // Add category
    if (isset($_POST['add_category']) && !empty($_POST['category_name'])) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $_POST['category_name']);
        $stmt->execute();
        $stmt->close();
    }

    // Delete category
    if (isset($_POST['delete_category'])) {
        $stmt = $conn->prepare("DELETE FROM categories WHERE category_id=?");
        $stmt->bind_param("i", $_POST['delete_category']);
        $stmt->execute();
        $stmt->close();
    }
}

// Dashboard stats
$totalBusinesses = $conn->query("SELECT COUNT(*) as total FROM businesses")->fetch_assoc()['total'];
$pendingBusinesses = $conn->query("SELECT COUNT(*) as total FROM businesses WHERE status='pending'")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// Fetch businesses, users, categories
$bizRes = $conn->query("SELECT * FROM businesses ORDER BY created_at DESC");
$userRes = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
$catRes = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-deepgreen fw-bold mb-4" style="font-family:'Playfair Display', serif;">Admin Dashboard</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item"><a class="nav-link <?php echo $tab=='dashboard'?'active':''; ?>" href="?tab=dashboard">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link <?php echo $tab=='businesses'?'active':''; ?>" href="?tab=businesses">Businesses</a></li>
      <li class="nav-item"><a class="nav-link <?php echo $tab=='users'?'active':''; ?>" href="?tab=users">Users</a></li>
      <li class="nav-item"><a class="nav-link <?php echo $tab=='categories'?'active':''; ?>" href="?tab=categories">Categories</a></li>
      <li class="nav-item ms-auto"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    </ul>

    <!-- Tab Content -->
    <div>
      <!-- DASHBOARD -->
      <?php if($tab=='dashboard'): ?>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card shadow-sm p-4 oldmoney-card text-center">
              <h5>Total Businesses</h5>
              <h3 class="text-deepgreen"><?php echo $totalBusinesses; ?></h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-4 oldmoney-card text-center">
              <h5>Pending Businesses</h5>
              <h3 class="text-warning"><?php echo $pendingBusinesses; ?></h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-4 oldmoney-card text-center">
              <h5>Total Users</h5>
              <h3 class="text-deepgreen"><?php echo $totalUsers; ?></h3>
            </div>
          </div>
        </div>

      <!-- BUSINESSES -->
      <?php elseif($tab=='businesses'): ?>
        <h4 class="fw-bold mb-3 text-deepgreen">Manage Businesses</h4>
        <table class="table table-hover oldmoney-card">
          <thead>
            <tr>
              <th>Name</th>
              <th>Category</th>
              <th>City</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while($b = $bizRes->fetch_assoc()): ?>
              <tr>
                <td><?php echo e($b['name']); ?></td>
                <td><?php echo e($b['category']); ?></td>
                <td><?php echo e($b['city']); ?></td>
                <td>
                  <?php echo $b['status']=='pending'?'<span class="badge bg-warning text-dark">Pending</span>':'<span class="badge bg-success">Approved</span>'; ?>
                </td>
                <td>
                  <?php if($b['status']=='pending'): ?>
                    <form method="post" class="d-inline">
                      <button name="approve_business" value="<?php echo $b['business_id']; ?>" class="btn btn-success btn-sm">Approve</button>
                    </form>
                  <?php endif; ?>
                  <form method="post" class="d-inline">
                    <button name="delete_business" value="<?php echo $b['business_id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

      <!-- USERS -->
      <?php elseif($tab=='users'): ?>
        <h4 class="fw-bold mb-3 text-deepgreen">Manage Users</h4>
        <table class="table table-hover oldmoney-card">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while($u = $userRes->fetch_assoc()): ?>
              <tr>
                <td><?php echo e($u['name']); ?></td>
                <td><?php echo e($u['email']); ?></td>
                <td><?php echo $u['is_admin']?'Admin':'User'; ?></td>
                <td>
                  <?php if(!$u['is_admin']): ?>
                    <form method="post" class="d-inline">
                      <button name="delete_user" value="<?php echo $u['user_id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

      <!-- CATEGORIES -->
      <?php elseif($tab=='categories'): ?>
        <h4 class="fw-bold mb-3 text-deepgreen">Manage Categories</h4>
        <form method="post" class="mb-3 d-flex gap-2">
          <input type="text" name="category_name" class="form-control" placeholder="New Category" required>
          <button class="btn btn-success" name="add_category">Add</button>
        </form>
        <ul class="list-group oldmoney-card">
          <?php while($c = $catRes->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?php echo e($c['name']); ?>
              <div>
                <form method="post" class="d-inline">
                  <button name="delete_category" value="<?php echo $c['category_id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
