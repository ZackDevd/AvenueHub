<?php
session_start();

// Clear all session data
session_unset();
session_destroy();

// Clear "remember me" cookie if set
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/");
}

// Redirect to homepage
header("Location: index.php");
exit;
?>
