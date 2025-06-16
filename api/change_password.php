<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Get POSTed JSON data
$data = json_decode(file_get_contents('php://input'), true);

$current_password = trim($data['current_password'] ?? '');
$new_password     = trim($data['new_password'] ?? '');
$confirm_password = trim($data['confirm_password'] ?? '');

// Basic validations
if (!$current_password || !$new_password || !$confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'New passwords do not match.']);
    exit;
}

try {
    // Get the current hashed password from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user || !password_verify($current_password, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
        exit;
    }

    // Hash and update new password
    $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE users SET password = :new_password WHERE user_id = :user_id");
    $update->bindParam(':new_password', $new_hashed);
    $update->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $update->execute();

    echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
