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

// Step 0: Check if user has a pending 'waiting' booking for the same car
$stmt = $pdo->prepare("
    SELECT booking_id FROM booking 
    WHERE user_id = :user_id AND car_id = :car_id AND status = 'waiting'
");
$stmt->execute([
    ':user_id' => $user_id,
    ':car_id' => $car_id
]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode([
        'status' => 'duplicate',
        'message' => '❌ You already have a pending booking for this car. Please wait for it to be processed or cancel it first.'
    ]);
    exit;
}


try {
    // Step 1: Get car's daily price and agency solde
    $stmt = $pdo->prepare("
        SELECT c.price_per_day, a.solde
        FROM car c
        JOIN agency a ON c.agency_id = a.agency_id
        WHERE c.car_id = :car_id
    ");
    $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $carData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carData) {
        throw new Exception("Car not found or agency info missing");
    }

    $price_per_day = (float) $carData['price_per_day'];
    $solde = (float) $carData['solde'];

    // Step 2: Calculate booking days
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $days = $start->diff($end)->days + 1;

    // Step 3: Calculate platform fee
    if ($days <= 3) {
        $fee = 0.10 * $price_per_day;
    } else {
        $fee = 0.05 * $total_price;
    }

    // Step 4: Check solde
    if ($solde >= $fee) {
        $status = 'waiting';
    } else {
        $status = 'canceled';
    }

    // Step 5: Insert booking
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

    if ($status === 'waiting') {
        echo json_encode(['status' => 'success', 'message' => 'Booking confirmed']);
    } else {
        echo json_encode(['status' => 'warning', 'message' => 'Insufficient agency balance. Booking marked as canceled.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

