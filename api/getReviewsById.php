<?php
session_start();
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
    // ✅ تقييمات السيارات من سيارات نشطة فقط
    $carStmt = $pdo->prepare("
        SELECT 'car' AS type, r.car_id, r.review_text, r.rating, r.created_at
        FROM car_review r
        JOIN car c ON r.car_id = c.car_id
        WHERE r.user_id = :user_id AND c.status = 'active'
        ORDER BY r.created_at DESC
    ");
    $carStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $carStmt->execute();
    $carReviews = $carStmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ تقييمات الوكالات من وكالات نشطة فقط
    $agencyStmt = $pdo->prepare("
        SELECT 'agency' AS type, r.agency_id, r.review_text, r.rating, r.created_at
        FROM agency_review r
        JOIN agency a ON r.agency_id = a.agency_id
        WHERE r.user_id = :user_id AND a.status = 'active'
        ORDER BY r.created_at DESC
    ");
    $agencyStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $agencyStmt->execute();
    $agencyReviews = $agencyStmt->fetchAll(PDO::FETCH_ASSOC);

    // إخراج البيانات كـ JSON
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
