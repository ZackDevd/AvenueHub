<?php
require_once 'db.php';
require_once 'includes/header.php';

$errors = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $errors[] = "Email is required.";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Here you would generate a real token and send email
            $success_msg = "If this email exists in our system, a reset link has been sent.";
        } else {
            $success_msg = "If this email exists in our system, a reset link has been sent.";
        }
    }
}
?>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card oldmoney-card shadow-sm p-4">
          <h3 class="fw-bold mb-4 text-center" style="font-family:'Playfair Display', serif;">Forgot Password</h3>

          <!-- Alerts -->
          <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success text-center oldmoney-alert">
              <?php echo htmlspecialchars($success_msg); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger oldmoney-alert">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                  <li><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <!-- Forgot Password Form -->
          <form method="post" action="forgot-password.php">
            <div class="mb-3">
              <label class="form-label">Enter your email</label>
              <input type="email" name="email" class="form-control oldmoney-input" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
            </div>
            <button type="submit" class="btn oldmoney-btn w-100">Send Reset Link</button>
          </form>

          <div class="text-center mt-3">
            <a href="login.php" class="text-success small">Back to Login</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
