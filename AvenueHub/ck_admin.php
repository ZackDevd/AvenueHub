<?php
require_once 'includes/db.php';

// === Admin details ===
$email = 'admin@avenuehub.com';
$newPassword = 'admin123';
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

// === Update query ===
$stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $newHash, $email);

if ($stmt->execute()) {
    echo "✅ Admin password updated successfully.<br>";
    echo "Email: $email<br>";
    echo "New Password: $newPassword<br>";
    echo "New Hash: $newHash";
} else {
    echo "❌ Error updating password: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
