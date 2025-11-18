<?php
include('includes/admin_header.php');

// === Handle Actions ===
if (isset($_GET['action']) && isset($_GET['id'])) {
    $business_id = intval($_GET['id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'approve':
            $query = "UPDATE businesses SET status = 'approved' WHERE business_id = $business_id";
            break;
        case 'reject':
            $query = "UPDATE businesses SET status = 'rejected' WHERE business_id = $business_id";
            break;
        case 'pending':
            $query = "UPDATE businesses SET status = 'pending' WHERE business_id = $business_id";
            break;
        case 'delete':
            $query = "DELETE FROM businesses WHERE business_id = $business_id";
            break;
        default:
            $query = "";
            break;
    }

    if (!empty($query)) {
        if (!$conn->query($query)) {
            die("Error: " . $conn->error);
        }
    }

    header("Location: businesse.php");
    exit;
}

// === Fetch All Businesses ===
$query = "SELECT * FROM businesses ORDER BY business_id DESC";
$result = $conn->query($query);
?>

<div class="admin-content">
  <h2 class="mb-4"><i class="fa fa-briefcase me-2"></i> Business Management</h2>

  <div class="table-responsive bg-white shadow-sm rounded-4 p-3">
    <table class="table align-middle table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Owner ID</th>
          <th>Category ID</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($biz = $result->fetch_assoc()): ?>
            <tr data-bs-toggle="modal" data-bs-target="#bizModal<?php echo $biz['business_id']; ?>" style="cursor:pointer;">
              <td><?php echo $biz['business_id']; ?></td>
              <td><?php echo htmlspecialchars($biz['name']); ?></td>
              <td><?php echo htmlspecialchars($biz['owner_id']); ?></td>
              <td><?php echo htmlspecialchars($biz['category_id']); ?></td>
              <td><?php echo htmlspecialchars($biz['email']); ?></td>
              <td><?php echo htmlspecialchars($biz['phone']); ?></td>
              <td><?php echo htmlspecialchars($biz['city']); ?></td>

              <td>
                <?php
                  $status = strtolower($biz['status']);
                  $badgeClass = match ($status) {
                      'approved' => 'bg-success',
                      'pending' => 'bg-warning text-dark',
                      'rejected' => 'bg-danger',
                      default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?php echo $badgeClass; ?>">
                  <?php echo ucfirst($biz['status']); ?>
                </span>
              </td>

              <td><?php echo date('d M Y', strtotime($biz['created_at'])); ?></td>
            </tr>

            <!-- Modal for business details -->
            <div class="modal fade" id="bizModal<?php echo $biz['business_id']; ?>" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($biz['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <p><strong>Owner ID:</strong> <?php echo htmlspecialchars($biz['owner_id']); ?></p>
                    <p><strong>Category ID:</strong> <?php echo htmlspecialchars($biz['category_id']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($biz['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($biz['phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($biz['address']); ?>, <?php echo htmlspecialchars($biz['city']); ?>, <?php echo htmlspecialchars($biz['state']); ?></p>
                    <p><strong>Website:</strong> 
                        <a href="<?php echo htmlspecialchars($biz['website']); ?>" target="_blank">
                            <?php echo htmlspecialchars($biz['website']); ?>
                        </a>
                    </p>
                    <p><strong>Verified:</strong> <?php echo $biz['is_verified'] ? 'Yes' : 'No'; ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($biz['status']); ?></p>
                    <p><strong>Created:</strong> <?php echo date('d M Y', strtotime($biz['created_at'])); ?></p>

                    <hr>

                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                      <a href="?action=approve&id=<?php echo $biz['business_id']; ?>" 
                        class="btn btn-sm btn-success">Approve</a>

                      <a href="?action=pending&id=<?php echo $biz['business_id']; ?>" 
                        class="btn btn-sm btn-warning text-dark">Mark Pending</a>

                      <a href="?action=reject&id=<?php echo $biz['business_id']; ?>" 
                        class="btn btn-sm btn-danger">Reject</a>

                      <a href="?action=delete&id=<?php echo $biz['business_id']; ?>" 
                        onclick="return confirm('Are you sure you want to delete this business?')" 
                        class="btn btn-sm btn-outline-danger">Delete</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center text-muted py-4">No businesses found.</td>
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
