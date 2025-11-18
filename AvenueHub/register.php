<?php
require_once 'includes/db.php';
include('includes/header.php');

// Initialize variables
$success = $error = "";

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);
    $phone     = trim($_POST['phone']);
    $city      = trim($_POST['city']);
    $state     = trim($_POST['state']);

    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, city, state) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $full_name, $email, $hashedPassword, $phone, $city, $state);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!-- Hero -->
<section class="hero-lux">
  <div class="container">
    <h1>Create Your Account</h1>
    <p>Join AvenueHub and discover a world of premium local businesses.</p>
  </div>
</section>

<!-- Registration Form -->
<section class="py-5">
  <div class="container" style="max-width: 600px;">
    <?php if ($success): ?>
      <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm" style="border-radius: 16px;">
      <form method="POST" action="">

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control">
          </div>
        </div>

        <button type="submit" class="btn btn-lux-primary w-100 mb-3">Register</button>

        <div class="text-center">
          <p class="mb-0">Already have an account? 
            <a href="login.php" style="color: var(--secondary-dark); text-decoration: none; font-weight: 600;">
              Login here
            </a>
          </p>
        </div>

      </form>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
