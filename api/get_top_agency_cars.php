<?php

header("Content-Type: application/json");
require_once '../includes/config.php';

if (!isset($_GET['agency_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing agency_id parameter"
    ]);
    exit;
}

$agency_id = intval($_GET['agency_id']);

try {
    $stmt = $pdo->prepare("
        SELECT 
            c.*, 
            a.name AS agency_name
        FROM 
            car c
        INNER JOIN 
            agency a ON c.agency_id = a.agency_id
        WHERE 
            c.agency_id = :agency_id
        ORDER BY 
            c.car_rating DESC
        LIMIT 3
    ");
    
    $stmt->execute(['agency_id' => $agency_id]);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "agency_id" => $agency_id,
        "cars" => $cars
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}

?>
