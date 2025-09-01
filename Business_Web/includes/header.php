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
    <!-- Left links -->
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link fw-semibold text-deepgreen" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-deepgreen" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-deepgreen" href="business-list.php">Browse</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold text-deepgreen" href="add-business.php">Add Business</a></li>
      </ul>

      <!-- Right buttons -->
      <ul class="navbar-nav ms-auto">
        <?php if (!empty($_SESSION['user'])): ?>
          <li class="nav-item">
            <a class="btn btn-outline-success oldmoney-btn" href="logout.php">Logout</a>
          </li>
          <?php if ($_SESSION['user']['is_admin'] == 1): ?>
            <li class="nav-item ms-2">
              <a class="nav-link text-warning fw-semibold" href="admin.php">Admin Panel</a>
            </li>
          <?php endif; ?>
        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-success oldmoney-btn" href="login.php">Login</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-outline-success oldmoney-btn" href="register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Navbar Toggler for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<style>
.oldmoney-navbar { 
    position: sticky; 
    top: 0; 
    z-index: 999; 
    background-color: #f8f8f5; 
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.oldmoney-btn { 
    border-radius: 50px; 
    font-weight: 500; 
}
.text-deepgreen { 
    color: #1f3b2c !important; 
}
.navbar-nav .nav-link {
    padding: 0.5rem 1rem;
}
</style>
