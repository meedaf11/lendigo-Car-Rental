<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

try {
    $stmt = $pdo->prepare("
        SELECT m.subject, m.message, m.submitted_at, m.answer, m.status
        FROM messages m
        JOIN users u ON m.user_id = u.user_id
        WHERE m.user_id = :user_id AND u.status = 'active'
        ORDER BY m.submitted_at DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $messages
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
