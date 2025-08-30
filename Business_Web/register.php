<?php
require_once 'db.php';
require_once 'includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if (!$name) $errors[] = "Full Name is required.";
    if (!$email) $errors[] = "Email is required.";
    if (!$password) $errors[] = "Password is required.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    // Check if email exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) $errors[] = "Email already registered.";
    }

    // Insert new user
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $hashed);
        if($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $errors[] = "Registration failed. Try again.";
        }
        $stmt->close();
    }
}
?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0 p-4 oldmoney-card">
          <h3 class="fw-bold mb-3 text-center text-deepgreen" style="font-family:'Playfair Display', serif;">Create an Account</h3>

          <?php if(!empty($errors)): ?>
            <div class="alert alert-danger oldmoney-alert">
              <ul class="mb-0">
                <?php foreach($errors as $e): ?>
                  <li><?php echo e($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="register.php" class="mt-3">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control oldmoney-input" required value="<?php echo e($_POST['name'] ?? ''); ?>" placeholder="John Doe">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control oldmoney-input" required value="<?php echo e($_POST['email'] ?? ''); ?>" placeholder="example@email.com">
            </div>

            <!-- Password -->
             <label class="form-label">Password</label>
<div class="mb-3 position-relative d-flex align-items-center">
  
  <input type="password" name="password" class="form-control oldmoney-input pe-5" required placeholder="********" id="password">
  <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
</div>

<!-- Confirm Password -->
 <label class="form-label">Confirm Password</label>
<div class="mb-3 position-relative d-flex align-items-center">
  
  <input type="password" name="confirm" class="form-control oldmoney-input pe-5" required placeholder="********" id="confirm">
  <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm', this)"></i>
</div>

            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" id="terms" required>
              <label class="form-check-label small" for="terms">
                I agree to the <a href="#" class="text-success">Terms & Conditions</a>
              </label>
            </div>

            <button class="btn oldmoney-btn w-100" type="submit">Register</button>
          </form>

          <p class="mt-3 text-center small">Already have an account? <a href="login.php" class="text-deepgreen fw-semibold">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* Toggle password eye */
.toggle-password {
  position: absolute;
  right: 15px;
  font-size: 1.1rem;
  cursor: pointer;
  color: #1f3b2c;
  transition: color 0.3s ease, transform 0.2s ease;
}
.toggle-password:hover {
  color: #c0aa83;
}

.toggle-password.showing {
  transform: translateY(-50%) translateX(3px);
  color: #c0aa83;
}
</style>

<script>
function togglePassword(fieldId, icon) {
  const field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
    icon.classList.add("showing");
  } else {
    field.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
    icon.classList.remove("showing");
  }
}
</script>

<?php require_once 'includes/footer.php'; ?>
