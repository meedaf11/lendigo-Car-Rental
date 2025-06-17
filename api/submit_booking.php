<?php
require_once '../includes/config.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get and decode JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (
    !isset($data['car_id'], $data['start_date'], $data['end_date'], $data['total_price']) ||
    empty($data['car_id']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['total_price'])
) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Sanitize and assign variables
$user_id = $_SESSION['user_id'];
$car_id = intval($data['car_id']);
$start_date = $data['start_date'];
$end_date = $data['end_date'];
$total_price = floatval($data['total_price']);
$booking_date = date('Y-m-d');
$status = 'waiting';

try {
    $sql = "INSERT INTO booking (user_id, car_id, booking_date, status, start_date, end_date, total_price)
            VALUES (:user_id, :car_id, :booking_date, :status, :start_date, :end_date, :total_price)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':car_id' => $car_id,
        ':booking_date' => $booking_date,
        ':status' => $status,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':total_price' => $total_price
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Booking saved successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
