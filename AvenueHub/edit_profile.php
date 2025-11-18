<?php
include('includes/header.php');
include('includes/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $profile_pic = $user['profile_pic'];

    // Handle image upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "uploads/profile_pics/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = uniqid() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $file_name;

        // Only allow certain formats
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = $target_file;
            }
        }
    }

    $update = "UPDATE users SET full_name=?, phone=?, city=?, state=?, profile_pic=? WHERE user_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssi", $full_name, $phone, $city, $state, $profile_pic, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Failed to update profile.";
    }
}
?>

<section class="hero-lux">
  <div class="container text-center">
    <h1>Edit Profile</h1>
    <p>Update your personal information below.</p>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="mx-auto shadow p-5 rounded-4" style="max-width:700px;background:#fff;">
      <h3 class="text-center mb-4" style="font-family:'Playfair Display',serif;font-weight:700;">Edit Your Details</h3>

      <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
      <?php } ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="text-center mb-4">
          <img id="previewImg" 
               src="<?php echo $user['profile_pic'] ? $user['profile_pic'] : 'assets/img/default-profile.png'; ?>" 
               class="rounded-circle shadow-sm" 
               width="120" height="120" 
               style="object-fit:cover;">
        </div>

        <div class="mb-3 text-center">
          <label class="form-label fw-semibold">Profile Picture</label>
          <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*">
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">City</label>
            <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">State</label>
            <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($user['state']); ?>">
          </div>
        </div>

        <div class="text-center mt-5">
          <button type="submit" class="btn btn-lux-primary me-2">Save Changes</button>
          <a href="profile.php" class="btn btn-outline-lux">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('profile_pic').addEventListener('change', function(e){
    const [file] = e.target.files;
    if (file) {
        document.getElementById('previewImg').src = URL.createObjectURL(file);
    }
});
</script>

<?php include('includes/footer.php'); ?>
