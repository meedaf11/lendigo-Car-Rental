<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Helper: clean input
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Get and sanitize inputs
$fullName = clean($_POST['fullName'] ?? '');
$username = clean($_POST['username'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$phone = clean($_POST['phoneNumber'] ?? '');

// ✅ Basic validation
if (!$fullName || !$username || !$email || !$password || !$confirmPassword || !$phone) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
}

try {
    // Check if email or username already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email OR username = :username");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email or username already exists.']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insert = $pdo->prepare("
        INSERT INTO users (username, email, password, phone_number, registration_date, user_type)
        VALUES (:username, :email, :password, :phone, CURDATE(), 'customer')
    ");
    $insert->bindParam(':username', $username);
    $insert->bindParam(':email', $email);
    $insert->bindParam(':password', $hashedPassword);
    $insert->bindParam(':phone', $phone);
    $insert->execute();

    echo json_encode(['success' => true, 'message' => 'Registration successful. You can now log in.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
