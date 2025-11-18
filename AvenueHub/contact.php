<?php
include('includes/header.php');
include('includes/db.php');

$message_sent = false;
$error_msg = "";

// === Handle form submission ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            $message_sent = true;
        } else {
            $error_msg = "Database error. Please try again later.";
        }
        $stmt->close();
    } else {
        $error_msg = "Please fill all required fields.";
    }
}
?>

<!-- === Hero Section === -->
<section class="hero-lux text-center">
  <div class="container">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you — let’s build something amazing together.</p>
  </div>
</section>

<!-- === Contact Form === -->
<section class="py-5">
  <div class="container">
    <div class="mx-auto shadow p-5 rounded-4" style="max-width:750px;background:#fff;">

      <?php if ($message_sent): ?>
        <div class="alert alert-success text-center">
          ✅ Thank you, your message has been sent successfully!
        </div>
      <?php elseif (!empty($error_msg)): ?>
        <div class="alert alert-danger text-center">
          ⚠️ <?php echo htmlspecialchars($error_msg); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="name" placeholder="Your name" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
          <input type="email" class="form-control" name="email" placeholder="example@email.com" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Phone Number</label>
          <input type="text" class="form-control" name="phone" placeholder="+91 98765 43210">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Subject</label>
          <input type="text" class="form-control" name="subject" placeholder="Subject of your message">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
          <textarea class="form-control" name="message" rows="5" placeholder="Write your message here..." required></textarea>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-lux-primary px-5">Send Message</button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
