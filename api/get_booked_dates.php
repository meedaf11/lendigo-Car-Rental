<?php
header("Content-Type: application/json");

// Connect to database
require_once '../includes/config.php'; 

if (!isset($_GET['car_id'])) {
    echo json_encode(["error" => "Missing car_id"]);
    exit;
}

$car_id = intval($_GET['car_id']);

// Use a clear named placeholder :car_id
$sql = "SELECT start_date, end_date FROM booking 
        WHERE car_id = :car_id AND status = 'reserved'";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);  // ✅ fetchAll

$bookedDates = [];

foreach ($results as $row) {
    $start = new DateTime($row['start_date']);
    $end = new DateTime($row['end_date']);

    while ($start <= $end) {
        $bookedDates[] = $start->format("Y-m-d");
        $start->modify("+1 day");
    }
}

echo json_encode(["bookedDates" => $bookedDates]);
