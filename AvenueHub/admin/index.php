<?php include('includes/admin_header.php'); ?>

<div class="admin-content">
  <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</h2>

  <div class="row g-4">
    <!-- Users Card -->
    <div class="col-md-4">
      <a href="users.php" class="text-decoration-none">
        <div class="admin-card hover-card text-center">
          <h5><i class="fa fa-users me-2"></i> Total Users</h5>
          <?php
          $res = $conn->query("SELECT COUNT(*) AS total FROM users");
          $row = $res->fetch_assoc();
          echo "<h3>{$row['total']}</h3>";
          ?>
        </div>
      </a>
    </div>

    <!-- Businesses Card -->
    <div class="col-md-4">
      <a href="businesse.php" class="text-decoration-none">
        <div class="admin-card hover-card text-center">
          <h5><i class="fa fa-briefcase me-2"></i> Total Businesses</h5>
          <?php
          $res = $conn->query("SELECT COUNT(*) AS total FROM businesses");
          $row = $res->fetch_assoc();
          echo "<h3>{$row['total']}</h3>";
          ?>
        </div>
      </a>
    </div>

    <!-- Contacts Card -->
    <div class="col-md-4">
      <a href="contacts.php" class="text-decoration-none">
        <div class="admin-card hover-card text-center">
          <h5><i class="fa fa-envelope me-2"></i> Contact Messages</h5>
          <?php
          $res = $conn->query("SELECT COUNT(*) AS total FROM contacts");
          $row = $res->fetch_assoc();
          echo "<h3>{$row['total']}</h3>";
          ?>
        </div>
      </a>
    </div>
  </div>
</div>

</body>
</html>
