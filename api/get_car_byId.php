<?php
header("Content-Type: application/json");
require_once '../includes/config.php';


if (!isset($_GET['car_id'])) {
    echo json_encode(["error" => "car_id is required"]);
    exit;
}

$car_id = intval($_GET['car_id']);

try {
    $query = "SELECT 
                c.*, 
                a.name AS agency_name, 
                a.contact_email, 
                a.phone_number, 
                a.agency_city, 
                a.location AS agency_location, 
                a.rating AS agency_rating
              FROM car c
              INNER JOIN agency a ON c.agency_id = a.agency_id
              WHERE c.car_id = :car_id and status = 'active'";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo json_encode(["error" => "Car not found"]);
    } else {
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($car);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>