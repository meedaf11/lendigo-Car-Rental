<?php
require_once 'functions.php';

$carId = $_GET['car_id'] ?? null;

if (!$carId) {
    echo "No car ID provided.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM car WHERE car_id = ?");
$stmt->execute([$carId]);
$car = $stmt->fetch();

if (!$car) {
    echo "Car not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Car</title>
    <link rel="stylesheet" href="css/editCars.css">
</head>

<body>
    <div class="edit-car-container">
        <h2>Edit Car: <?= htmlspecialchars($car['car_name']) ?></h2>
        <form action="update_or_delete_car.php" method="POST">
            <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">

            <label>Car Name: <input type="text" name="car_name" value="<?= htmlspecialchars($car['car_name']) ?>"></label><br>
            <label>Brand: <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>"></label><br>
            <label>Model: <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>"></label><br>
            <label>Image URL: <input type="text" name="image_url" value="<?= htmlspecialchars($car['image_url']) ?>"></label><br>
            <label>Fuel Type: <input type="text" name="car_fuel" value="<?= htmlspecialchars($car['car_fuel']) ?>"></label><br>
            <label>Seats: <input type="number" name="places" value="<?= $car['places'] ?>"></label><br>
            <label>Price/Day: <input type="number" name="price_per_day" step="0.01" value="<?= $car['price_per_day'] ?>"></label><br>
            <label>Description: <textarea name="description"><?= htmlspecialchars($car['description']) ?></textarea></label><br>

            <div class="edit-car-buttons">
                <button type="submit" name="action" value="edit">Edit</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Delete this car?');">Delete</button>
            </div>
        </form>
    </div>


</body>

</html>