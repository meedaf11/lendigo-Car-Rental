<?php
require_once '../includes/config.php'; // session_start + DB connection
header('Content-Type: application/json; charset=UTF-8');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'You must be logged in to submit a review.'
    ]);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Read and validate JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (
    !isset($input['car_id'], $input['rating'], $input['review_text']) ||
    !is_numeric($input['car_id']) ||
    !is_numeric($input['rating']) ||
    $input['rating'] < 1 || $input['rating'] > 5
) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input data.'
    ]);
    exit;
}

$car_id = intval($input['car_id']);
$rating = floatval($input['rating']);
$review_text = trim($input['review_text']);

try {
    $stmt = $pdo->prepare("
        INSERT INTO car_review (user_id, car_id, rating, review_text, created_at)
        VALUES (:user_id, :car_id, :rating, :review_text, CURDATE())
    ");

    // Bind values
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);

    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Car review submitted successfully.'
    ]);
} catch (PDOException $e) {
    error_log("Submit Car Review Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error. Please try again later.'
    ]);
}
