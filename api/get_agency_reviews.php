<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// Check for agency_id in query
if (!isset($_GET['agency_id']) || !is_numeric($_GET['agency_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing agency_id']);
    exit;
}

$agency_id = (int) $_GET['agency_id'];

try {
    $stmt = $pdo->prepare("
        SELECT ar.rating, ar.review_text, u.username
        FROM agency_review ar
        JOIN users u ON ar.user_id = u.user_id
        WHERE ar.agency_id = :agency_id and status = 'active'
        ORDER BY ar.created_at DESC
    ");
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $reviews
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
