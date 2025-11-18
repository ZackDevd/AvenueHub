<?php
session_start();
require_once 'includes/db.php';
include('includes/header.php');

$error = "";

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill all required fields.";
    } else {

        // --- Step 1: Check admin table ---
        $stmtAdmin = $conn->prepare("SELECT admin_id, username, email, password FROM admin WHERE email = ?");
        $stmtAdmin->bind_param("s", $email);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        if ($resultAdmin->num_rows === 1) {
            $admin = $resultAdmin->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                // ✅ Admin login success
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: admin/index.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            // --- Step 2: Check user table ---
            $stmtUser = $conn->prepare("SELECT user_id, full_name, email, password, role FROM users WHERE email = ?");
            $stmtUser->bind_param("s", $email);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();

            if ($resultUser->num_rows === 1) {
                $user = $resultUser->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No account found with that email.";
            }

            $stmtUser->close();
        }

        $stmtAdmin->close();
    }
}
?>

<!-- Hero -->
<section class="hero-lux">
  <div class="container">
    <h1>Welcome Back</h1>
    <p>Login to continue exploring premium local businesses.</p>
  </div>
</section>

<!-- Login Form -->
<section class="py-5">
  <div class="container" style="max-width: 500px;">
    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm" style="border-radius: 16px;">
      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-lux-primary w-100 mb-3">Login</button>

        <div class="text-center">
          <p class="mb-0">Don’t have an account? 
            <a href="register.php" style="color: var(--secondary-dark); text-decoration: none; font-weight: 600;">
              Register here
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
