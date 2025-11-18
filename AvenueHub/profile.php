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
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!-- Hero Section -->
<section class="hero-lux text-center">
     <div class="container">
          <h1>Your Profile</h1>
          <p>Manage your personal information and preferences.</p>
     </div>
</section>

<!-- Profile Section -->
<section class="py-5">
     <div class="container">
          <div class="mx-auto shadow p-5 rounded-4" style="max-width:750px;background:#fff;">
               <div class="text-center mb-4">
                    <img src="<?php echo $user['profile_pic'] ? htmlspecialchars($user['profile_pic']) : 'assets/img/default-profile.png'; ?>" 
                         alt="Profile Picture"
                         class="rounded-circle shadow-sm border"
                         width="130" height="130"
                         style="object-fit:cover;">
                    <h3 class="mt-3" style="font-family:'Playfair Display',serif;font-weight:700;">
                         <?php echo htmlspecialchars($user['full_name']); ?>
                    </h3>
                    <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
               </div>

               <hr class="my-4">

               <div class="row g-3">
                    <div class="col-md-6">
                         <div class="profile-field">
                              <h6 class="fw-semibold text-muted mb-1">Phone</h6>
                              <p class="fs-6 text-dark mb-0"><?php echo htmlspecialchars($user['phone']); ?></p>
                         </div>
                    </div>
                    <div class="col-md-6">
                         <div class="profile-field">
                              <h6 class="fw-semibold text-muted mb-1">City</h6>
                              <p class="fs-6 text-dark mb-0"><?php echo htmlspecialchars($user['city']); ?></p>
                         </div>
                    </div>
                    <div class="col-md-6">
                         <div class="profile-field">
                              <h6 class="fw-semibold text-muted mb-1">State</h6>
                              <p class="fs-6 text-dark mb-0"><?php echo htmlspecialchars($user['state']); ?></p>
                         </div>
                    </div>
                    <div class="col-md-6">
                         <div class="profile-field">
                              <h6 class="fw-semibold text-muted mb-1">Member Since</h6>
                              <p class="fs-6 text-dark mb-0">
                                   <?php echo date("d M Y", strtotime($user['created_at'])); ?>
                              </p>
                         </div>
                    </div>
               </div>

               <div class="text-center mt-5">
                    <a href="edit_profile.php" class="btn btn-lux-primary me-2">Edit Profile</a>
                    <a href="logout.php" class="btn btn-outline-lux">Logout</a>
               </div>
          </div>
     </div>
</section>

<?php include('includes/footer.php'); ?>
