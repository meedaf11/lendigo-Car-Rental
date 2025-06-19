<?php
require_once __DIR__ . '/functions.php';

$carId = $_POST['car_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$carId || !$action) {
    echo "Invalid form data.";
    exit;
}

if ($action === 'edit') {
    $stmt = $pdo->prepare("
        UPDATE car SET 
            car_name = :car_name,
            brand = :brand,
            model = :model,
            car_fuel = :car_fuel,
            places = :places,
            price_per_day = :price_per_day,
            description = :description,
            image_url = :image_url,
            car_type = :car_type,
            availability_status = :availability_status,
            kilometers = :kilometers,
            isAutomatic = :isAutomatic
        WHERE car_id = :car_id
    ");

    $stmt->bindParam(':car_name', $_POST['car_name']);
    $stmt->bindParam(':brand', $_POST['brand']);
    $stmt->bindParam(':model', $_POST['model']);
    $stmt->bindParam(':car_fuel', $_POST['car_fuel']);
    $stmt->bindParam(':places', $_POST['places'], PDO::PARAM_INT);
    $stmt->bindParam(':price_per_day', $_POST['price_per_day']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':image_url', $_POST['image_url']);
    $stmt->bindParam(':car_type', $_POST['car_type']);
    $stmt->bindParam(':availability_status', $_POST['availability_status']);
    $stmt->bindParam(':kilometers', $_POST['kilometers'], PDO::PARAM_INT);

    $isAutomatic = isset($_POST['isAutomatic']) ? 1 : 0;
    $stmt->bindParam(':isAutomatic', $isAutomatic, PDO::PARAM_INT);
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);

    $stmt->execute();

    header("Location: edit_car.php?car_id=$carId&status=updated");


    exit;
}

if ($action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM car WHERE car_id = :car_id");
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: index.php?page=cars&status=deleted");
    exit;
}
