<?php
require_once __DIR__ . '/functions.php';

// Ensure data came from a form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add_car.php");
    exit;
}

try {
    $pdo->beginTransaction();

    // Required fields
    $agency_id = $_SESSION['agency_id'] ?? null;
    $car_name = $_POST['car_name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $car_type = $_POST['car_type'];
    $image_url = $_POST['image_url'];
    $car_fuel = $_POST['car_fuel'];
    $isAutomatic = isset($_POST['isAutomatic']) ? (int)$_POST['isAutomatic'] : 0;
    $places = (int)$_POST['places'];
    $price_per_day = (float)$_POST['price_per_day'];
    $kilometers = (int)$_POST['kilometers'];
    $availability_status = $_POST['availability_status'];
    $description = $_POST['description'];
    $car_rating = $_POST['car_rating'] ?? 0.0;

    // Prepare insert
    $stmt = $pdo->prepare("
        INSERT INTO car (
            agency_id, car_name, brand, model, car_type, image_url, car_fuel,
            isAutomatic, places, price_per_day, kilometers, availability_status,
            description, car_rating
        ) VALUES (
            :agency_id, :car_name, :brand, :model, :car_type, :image_url, :car_fuel,
            :isAutomatic, :places, :price_per_day, :kilometers, :availability_status,
            :description, :car_rating
        )
    ");

    // Bind values
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    $stmt->bindParam(':car_name', $car_name);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':car_type', $car_type);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':car_fuel', $car_fuel);
    $stmt->bindParam(':isAutomatic', $isAutomatic, PDO::PARAM_INT);
    $stmt->bindParam(':places', $places, PDO::PARAM_INT);
    $stmt->bindParam(':price_per_day', $price_per_day);
    $stmt->bindParam(':kilometers', $kilometers, PDO::PARAM_INT);
    $stmt->bindParam(':availability_status', $availability_status);
    $stmt->bindParam(':car_rating', $car_rating);
    $stmt->bindParam(':description', $description);

    $stmt->execute();
    $pdo->commit();

    // Redirect with success message
    header("Location: index.php?page=cars&status=car_added");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo "<h3>Error inserting car: " . htmlspecialchars($e->getMessage()) . "</h3>";
    echo "<a href='add_car.php'>← Back to Add Car</a>";
    exit;
}
