<?php
require_once 'db.php';
require_once 'includes/header.php';

// Only logged-in users
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = false;

// Fetch categories
$cats = [];
$res = $conn->query("SELECT name FROM categories ORDER BY name ASC");
while($row = $res->fetch_assoc()) $cats[] = $row['name'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = 'pending'; // or 'approved'

    // Validation
    if (!$name) $errors[] = "Business name is required.";
    if (!$category) $errors[] = "Category is required.";
    if (!$city) $errors[] = "City is required.";

    // Handle image upload
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "assets/images/uploads/";
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $ext;
        $targetFile = $targetDir . $imageName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $errors[] = "Failed to upload image.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO businesses (name, category, city, state, address, phone, description, image, status, created_at) VALUES (?,?,?,?,?,?,?,?,?,NOW())");
        $stmt->bind_param("sssssssss", $name, $category, $city, $state, $address, $phone, $description, $imageName, $status);
        if ($stmt->execute()) $success = true;
        else $errors[] = "Failed to add business.";
        $stmt->close();
    }
}
?>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="card oldmoney-card shadow-sm border-0 p-4">
          <h3 class="fw-bold mb-4 text-center" style="font-family:'Playfair Display', serif;">Add New Business</h3>

          <?php if($success): ?>
            <div class="alert alert-success">Business submitted successfully! Awaiting approval.</div>
          <?php endif; ?>

          <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach($errors as $e): ?>
                  <li><?php echo e($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="add-business.php" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Business Name</label>
              <input type="text" name="name" class="form-control oldmoney-input" required value="<?php echo e($_POST['name'] ?? ''); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select name="category" class="form-select oldmoney-select" required>
                <option value="">Select category</option>
                <?php foreach($cats as $c): ?>
                  <option value="<?php echo e($c); ?>" <?php echo (($_POST['category']??'')===$c)?'selected':''; ?>>
                    <?php echo e($c); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3 row">
              <div class="col">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control oldmoney-input" required value="<?php echo e($_POST['city'] ?? ''); ?>">
              </div>
              <div class="col">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control oldmoney-input" value="<?php echo e($_POST['state'] ?? ''); ?>">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control oldmoney-input" value="<?php echo e($_POST['address'] ?? ''); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control oldmoney-input" value="<?php echo e($_POST['phone'] ?? ''); ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control oldmoney-input" rows="5"><?php echo e($_POST['description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control oldmoney-input">
            </div>
            <button class="btn oldmoney-btn w-100" type="submit">Submit Business</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
