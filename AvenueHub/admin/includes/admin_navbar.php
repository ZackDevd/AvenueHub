<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<div class="admin-sidebar">
  <div class="sidebar-header text-center py-3">
    <h4 class="fw-bold text-white">AvenueHub</h4>
    <small class="text-light">Admin Panel</small>
  </div>

  <a href="../admin/index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
    <i class="fa fa-home me-2"></i> Dashboard
  </a>
  
  <a href="../admin/users.php" class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
    <i class="fa fa-users me-2"></i> Users
  </a>
  
  <a href="../admin/businesse.php" class="<?php echo ($current_page == 'businesses.php') ? 'active' : ''; ?>">
    <i class="fa fa-store me-2"></i> Businesses
  </a>
  
  <a href="../admin/contacts.php" class="<?php echo ($current_page == 'contacts.php') ? 'active' : ''; ?>">
    <i class="fa fa-envelope me-2"></i> Contacts
  </a>

  <a href="../admin/logout.php" class="text-danger">
    <i class="fa fa-sign-out-alt me-2"></i> Logout
  </a>
</div>

<!-- Topbar -->
<nav class="admin-topbar d-flex justify-content-between align-items-center">
  <h5 class="m-0">
    <?php
      // Display current section title dynamically
      switch ($current_page) {
        case 'users.php': echo "Users Management"; break;
        case 'businesses.php': echo "Business Listings"; break;
        case 'contacts.php': echo "Contact Messages"; break;
        default: echo "Dashboard";
      }
    ?>
  </h5>
  <div class="d-flex align-items-center">
    <span class="me-3 fw-semibold text-dark">
      <i class="fa fa-user-circle me-1"></i>
      <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
    </span>
  </div>
</nav>
