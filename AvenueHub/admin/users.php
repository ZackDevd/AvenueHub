<?php
include('includes/admin_header.php');

// === Handle Actions ===
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'make_admin':
            $conn->query("UPDATE users SET role = 'admin' WHERE user_id = $user_id");
            break;
        case 'deactivate':
            $conn->query("UPDATE users SET role = 'inactive' WHERE user_id = $user_id");
            break;
        case 'activate':
            $conn->query("UPDATE users SET role = 'user' WHERE user_id = $user_id");
            break;
        case 'delete':
            $conn->query("DELETE FROM users WHERE user_id = $user_id");
            break;
    }

    header("Location: users.php");
    exit;
}

// === Fetch All Users ===
$query = "SELECT * FROM users ORDER BY user_id DESC";
$result = $conn->query($query);
?>

<div class="admin-content">
  <h2 class="mb-4">User Management</h2>

  <div class="table-responsive bg-white shadow-sm rounded-4 p-3">
    <table class="table align-middle table-hover">
      <thead class="table-light">
        <tr>
          <th>Id</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($user = $result->fetch_assoc()): ?>
            <tr data-bs-toggle="modal" data-bs-target="#userModal<?php echo $user['user_id']; ?>" style="cursor:pointer;">
              <td><?php echo $user['user_id']; ?></td>
              <td><?php echo htmlspecialchars($user['full_name']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td><?php echo htmlspecialchars($user['phone']); ?></td>
              <td><?php echo htmlspecialchars($user['city']); ?></td>

              <td>
                <?php
                  $role = strtolower($user['role']);
                  $badgeClass = match ($role) {
                      'admin' => 'bg-success',
                      'inactive' => 'bg-secondary',
                      default => 'bg-primary'
                  };
                ?>
                <span class="badge <?php echo $badgeClass; ?>">
                  <?php echo ucfirst($user['role']); ?>
                </span>
              </td>

              <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
            </tr>

            <!-- Modal for user details -->
            <div class="modal fade" id="userModal<?php echo $user['user_id']; ?>" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                    <p><strong>State:</strong> <?php echo htmlspecialchars($user['state']); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                    <p><strong>Created:</strong> <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>

                    <hr>

                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                      <?php if ($user['role'] !== 'admin'): ?>
                        <a href="?action=activate&id=<?php echo $user['user_id']; ?>" 
                          class="btn btn-sm btn-success">Activate</a>

                        <a href="?action=deactivate&id=<?php echo $user['user_id']; ?>" 
                          class="btn btn-sm btn-warning">Deactivate</a>

                        <a href="?action=make_admin&id=<?php echo $user['user_id']; ?>" 
                          class="btn btn-sm btn-info">Make Admin</a>

                        <a href="?action=delete&id=<?php echo $user['user_id']; ?>" 
                          onclick="return confirm('Delete this user?')" 
                          class="btn btn-sm btn-danger">Delete</a>
                      <?php else: ?>
                        <span class="text-muted">Admin (Locked)</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
