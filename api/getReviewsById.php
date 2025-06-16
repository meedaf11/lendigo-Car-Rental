<?php
session_start();
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

try {
    // Fetch car reviews
    $carStmt = $pdo->prepare("
        SELECT 'car' AS type,car_id, review_text, rating, created_at
        FROM car_review
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ");
    $carStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $carStmt->execute();
    $carReviews = $carStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch agency reviews
    $agencyStmt = $pdo->prepare("
        SELECT 'agency' AS type, agency_id, review_text, rating, created_at
        FROM agency_review
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ");
    $agencyStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $agencyStmt->execute();
    $agencyReviews = $agencyStmt->fetchAll(PDO::FETCH_ASSOC);

    // Return as grouped JSON
    echo json_encode([
        'status' => 'success',
        'data' => [
            'car_reviews' => $carReviews,
            'agency_reviews' => $agencyReviews
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
