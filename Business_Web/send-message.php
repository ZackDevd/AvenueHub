<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (!$name || !$email || !$message) {
        $_SESSION['error'] = "Please fill all required fields.";
        header("Location: contact.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: contact.php");
        exit;
    }

    try {
        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        $stmt->execute();
        $stmt->close();

        // OPTIONAL: Send email notification to admin
        $to      = "admin@avenuehub.com"; // change to your email
        $subject = "New Contact Message from $name";
        $body    = "You received a new message:\n\nName: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
        $headers = "From: noreply@avenuehub.com";

        // Uncomment if mail is configured on server
        // mail($to, $subject, $body, $headers);

        $_SESSION['success'] = "Your message has been sent successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }

    header("Location: contact.php");
    exit;
}
