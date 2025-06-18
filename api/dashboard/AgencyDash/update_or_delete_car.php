<?php
require_once __DIR__ . '/../functions.php';
global $pdo;

$carId = $_POST['car_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$carId || !$action) {
    echo "Invalid form data.";
    exit;
}

if ($action === 'edit') {
    $stmt = $pdo->prepare("UPDATE car SET car_name = ?, brand = ?, model = ?, car_fuel = ?, places = ?, price_per_day = ?, description = ? WHERE car_id = ?");
    $stmt->execute([
        $_POST['car_name'],
        $_POST['brand'],
        $_POST['model'],
        $_POST['car_fuel'],
        $_POST['places'],
        $_POST['price_per_day'],
        $_POST['description'],
        $carId
    ]);
    header("Location: agency_cars.php?status=updated");
    exit;
}

if ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM car WHERE car_id = ?");
    $stmt->execute([$carId]);
    header("Location: agency_cars.php?status=deleted");
    exit;
}
