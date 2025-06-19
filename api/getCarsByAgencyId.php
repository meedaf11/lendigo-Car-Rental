<?php
header("Content-Type: application/json");
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Invalid or missing agency ID"]);
    exit;
}

$agency_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("
        SELECT * 
        FROM car 
        WHERE agency_id = :agency_id 
        AND status = 'active'
    ");
    $stmt->bindParam(':agency_id', $agency_id);
    $stmt->execute();

    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($cars);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch cars"]);
}
?>
