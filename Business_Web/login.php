<?php
require_once 'db.php';
require_once 'includes/header.php';


$errors = [];
$cookie_email = $_COOKIE['remember_email'] ?? '';
$cookie_pass  = $_COOKIE['remember_pass'] ?? '';

// Encryption key for password in cookie (keep it secret & unique!)
define('REMEMBER_KEY', 'YOUR_SECRET_KEY_HERE');

function encryptCookie($data) {
    return openssl_encrypt($data, 'AES-128-ECB', REMEMBER_KEY);
}

function decryptCookie($data) {
    return openssl_decrypt($data, 'AES-128-ECB', REMEMBER_KEY);
}

// Decrypt password from cookie if exists
if ($cookie_pass) {
    $cookie_pass = decryptCookie($cookie_pass);
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (!$email) $errors[] = "Email is required.";
    if (!$password) $errors[] = "Password is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id, name, email, password, is_admin FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Store session
            $_SESSION['user'] = [
                'id'       => $user['user_id'],
                'name'     => $user['name'],
                'email'    => $user['email'],
                'is_admin' => $user['is_admin']
            ];

            // ✅ Remember Me (7 days)
            if ($remember) {
                setcookie('remember_email', $email, time() + 604800, '/'); // 7 days
                setcookie('remember_pass', encryptCookie($password), time() + 604800, '/');
            } else {
                setcookie('remember_email', '', time() - 3600, '/');
                setcookie('remember_pass', '', time() - 3600, '/');
            }

            // ✅ Redirect based on role
            if ($user['is_admin'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0 p-4 oldmoney-card">
          <h3 class="fw-bold mb-3 text-center text-deepgreen" style="font-family:'Playfair Display', serif;">Login</h3>

          <?php if(!empty($errors)): ?>
            <div class="alert alert-danger oldmoney-alert">
              <ul class="mb-0">
                <?php foreach($errors as $e): ?>
                  <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="login.php" class="mt-3">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control oldmoney-input" required 
                     value="<?php echo htmlspecialchars($_POST['email'] ?? $cookie_email ?? ''); ?>" placeholder="example@email.com">
            </div>

            <!-- Password Field -->
            <label class="form-label w-100">Password</label>
            <div class="mb-3 position-relative d-flex align-items-center">
              <input type="password" name="password" class="form-control oldmoney-input pe-5" required placeholder="********" id="password"
                     value="<?php echo htmlspecialchars($_POST['password'] ?? $cookie_pass ?? ''); ?>">
              <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>

            <div class="mb-3 form-check d-flex justify-content-between">
              <div>
                <input type="checkbox" name="remember" class="form-check-input" id="remember" 
                       <?php echo isset($_POST['remember']) || $cookie_email ? 'checked' : ''; ?>>
                <label class="form-check-label small" for="remember">Remember Me</label>
              </div>
              <div>
                <a href="forgot-password.php" class="text-success small">Forgot Password?</a>
              </div>
            </div>

            <button class="btn oldmoney-btn w-100" type="submit">Login</button>
          </form>

          <p class="mt-3 text-center small">Don't have an account? 
            <a href="register.php" class="text-deepgreen fw-semibold">Register</a>
          </p>
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
