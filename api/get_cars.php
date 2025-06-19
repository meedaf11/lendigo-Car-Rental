<?php

require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

try {
    $sql = "
        SELECT 
            car.*, 
            agency.agency_city 
        FROM car 
        INNER JOIN agency ON car.agency_id = agency.agency_id
        WHERE car.status = 'active' AND agency.status = 'active'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cars);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => "Database error: " . $e->getMessage()
    ]);
    exit();
}
