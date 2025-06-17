<?php
require_once '../includes/config.php'; // session_start + DB connection
header('Content-Type: application/json; charset=UTF-8');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized. Please log in."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $query = "
        SELECT agency_id AS id, name, image, location, created_at,
        (SELECT COUNT(*) FROM car WHERE car.agency_id = agency.agency_id ) AS car_count 
        FROM agency WHERE agency_owner_id = :user_id ORDER BY created_at DESC;
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $agencies
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
