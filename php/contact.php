<?php
require_once 'config.php';
setCorsHeaders();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.');
}

// Sanitize inputs
$name    = trim(htmlspecialchars($_POST['name']    ?? ''));
$email   = trim(htmlspecialchars($_POST['email']   ?? ''));
$subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));

// Server-side validation
if (strlen($name) < 2)                  jsonResponse(false, 'Name must be at least 2 characters.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(false, 'Invalid email address.');
if (strlen($subject) < 3)               jsonResponse(false, 'Subject must be at least 3 characters.');
if (strlen($message) < 20)              jsonResponse(false, 'Message must be at least 20 characters.');

$conn = getConnection();
$stmt = $conn->prepare(
    'INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())'
);
$stmt->bind_param('ssss', $name, $email, $subject, $message);

if ($stmt->execute()) {
    jsonResponse(true, 'Message sent successfully!');
} else {
    jsonResponse(false, 'Failed to save message. Please try again.');
}
$stmt->close();
$conn->close();
