<?php
include('includes/admin_header.php');

// === Handle Actions ===
if (isset($_GET['action']) && isset($_GET['id'])) {
    $contact_id = intval($_GET['id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'delete':
            if (!$conn->query("DELETE FROM contacts WHERE contact_id = $contact_id")) {
                die("Error deleting contact: " . $conn->error);
            }
            break;
    }

    header("Location: contacts.php");
    exit;
}

// === Fetch All Contacts ===
$query = "SELECT * FROM contacts ORDER BY contact_id DESC";
$result = $conn->query($query);
?>

<div class="admin-content">
  <h2 class="mb-4"><i class="fa fa-envelope me-2"></i> Contact Messages</h2>

  <div class="table-responsive bg-white shadow-sm rounded-4 p-3">
    <table class="table align-middle table-hover">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Subject</th>
          <th>Submitted</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($contact = $result->fetch_assoc()): ?>
            <tr data-bs-toggle="modal" data-bs-target="#contactModal<?php echo $contact['contact_id']; ?>" style="cursor:pointer;">
              <td><?php echo $contact['contact_id']; ?></td>
              <td><?php echo htmlspecialchars($contact['name']); ?></td>
              <td><?php echo htmlspecialchars($contact['email']); ?></td>
              <td><?php echo htmlspecialchars($contact['phone']); ?></td>
              <td><?php echo htmlspecialchars($contact['subject']); ?></td>
              <td><?php echo date('d M Y', strtotime($contact['submitted_at'])); ?></td>
            </tr>

            <!-- Modal for contact details -->
            <div class="modal fade" id="contactModal<?php echo $contact['contact_id']; ?>" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($contact['subject']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($contact['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($contact['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($contact['phone']); ?></p>
                    <p><strong>Message:</strong></p>
                    <div class="border rounded-3 p-2 bg-light mb-3">
                      <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                    </div>
                    <p><strong>Submitted:</strong> <?php echo date('d M Y, h:i A', strtotime($contact['submitted_at'])); ?></p>

                    <hr>

                    <div class="d-flex justify-content-center">
                      <a href="?action=delete&id=<?php echo $contact['contact_id']; ?>" 
                        onclick="return confirm('Delete this message?')" 
                        class="btn btn-sm btn-danger">Delete</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No contact messages found.</td>
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
