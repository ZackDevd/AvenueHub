<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db.php';

// Escape helper
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Avenue Hub — Find Local Businesses</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font-Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom Styles -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg oldmoney-navbar py-3">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Avenue Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navMain" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <!-- Browse and Add Business visible to all -->
        <li class="nav-item"><a class="nav-link" href="business-list.php">Browse</a></li>
        <li class="nav-item"><a class="nav-link" href="add-business.php">Add Business</a></li>

        <?php if (!empty($_SESSION['user'])): ?>
          <!-- Logout visible for logged-in users -->
          <li class="nav-item">
            <a class="btn btn-outline-success ms-lg-2" href="logout.php">Logout</a>
          </li>

          <?php if ($_SESSION['user']['is_admin'] == 1): ?>
            <!-- Admin Panel visible only for admins -->
            <li class="nav-item">
              <a class="nav-link text-warning fw-semibold" href="admin.php">Admin Panel</a>
            </li>
          <?php endif; ?>

        <?php else: ?>
          <!-- Login/Register visible only for guests -->
          <li class="nav-item">
            <a class="btn btn-success ms-lg-2" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-success ms-lg-2" href="register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

