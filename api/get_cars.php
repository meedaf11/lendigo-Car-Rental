<?php

require_once '../includes/config.php';


header('Content-Type: application/json; charset=UTF-8');

try {

    $sql = " SELECT * FROM car "; 
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





?>