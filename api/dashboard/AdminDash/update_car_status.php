<?php
require_once 'functions.php';

$data = json_decode(file_get_contents("php://input"), true);
$car_id = intval($data['car_id'] ?? 0);
$new_status = $data['new_status'] ?? '';

if (!$car_id || !in_array($new_status, ['active', 'blocked'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE car SET status = :status WHERE car_id = :car_id");
    $stmt->execute([
        ':status' => $new_status,
        ':car_id' => $car_id
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
