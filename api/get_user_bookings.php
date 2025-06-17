<?php
header("Content-Type: application/json");
require_once '../includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "
        SELECT 
            b.booking_id, b.car_id, b.booking_date, b.status, 
            b.start_date, b.end_date, b.total_price,
            c.car_name, c.model, c.image_url, c.price_per_day
        FROM booking b
        JOIN car c ON b.car_id = c.car_id
        WHERE b.user_id = :user_id
        ORDER BY b.booking_date DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'bookings' => $bookings
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
