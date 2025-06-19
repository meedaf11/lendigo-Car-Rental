<?php
require_once __DIR__ . '/functions.php';

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

if (isset($_GET['status']) && $_GET['status'] === 'updated') {
    echo "<script>alert('Car updated successfully!');</script>";
}


?>

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

            <label>Car Name:
                <input type="text" name="car_name" value="<?= htmlspecialchars($car['car_name']) ?>">
            </label><br>

            <label>Brand:
                <input type="text" name="brand" value="<?= htmlspecialchars($car['brand']) ?>">
            </label><br>

            <label>Model:
                <input type="text" name="model" value="<?= htmlspecialchars($car['model']) ?>">
            </label><br>

            <label>Image URL:
                <input type="text" name="image_url" value="<?= htmlspecialchars($car['image_url']) ?>">
            </label><br>

            <label>Fuel Type:
                <select name="car_fuel">
                    <option value="Diesel" <?= $car['car_fuel'] === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                    <option value="Gasoline" <?= $car['car_fuel'] === 'Gasoline' ? 'selected' : '' ?>>Gasoline</option>
                    <option value="Hybrid" <?= $car['car_fuel'] === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    <option value="Electrical" <?= $car['car_fuel'] === 'Electrical' ? 'selected' : '' ?>>Electrical
                    </option>
                </select>
            </label><br>

            <label>Seats:
                <input type="number" name="places" value="<?= $car['places'] ?>">
            </label><br>

            <label>Price/Day:
                <input type="number" name="price_per_day" step="0.01" value="<?= $car['price_per_day'] ?>">
            </label><br>

            <label>Description:
                <textarea name="description"><?= htmlspecialchars($car['description']) ?></textarea>
            </label><br>

            <label>Car Type:
                <input type="text" name="car_type" value="<?= htmlspecialchars($car['car_type']) ?>">
            </label><br>

            <label>Availability:
                <select name="availability_status">
                    <option value="available" <?= $car['availability_status'] === 'available' ? 'selected' : '' ?>>
                        Available</option>
                    <option value="booked" <?= $car['availability_status'] === 'booked' ? 'selected' : '' ?>>Booked
                    </option>
                </select>
            </label><br>

            <label>Kilometers:
                <input type="number" name="kilometers" value="<?= $car['kilometers'] ?>">
            </label><br>

            <label>Automatic:
                <select name="isAutomatic">
                    <option value="1" <?= $car['isAutomatic'] == 1 ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= $car['isAutomatic'] == 0 ? 'selected' : '' ?>>No</option>
                </select>
            </label>

            <div class="edit-car-buttons">
                <button type="submit" name="action" value="edit">Edit</button>
                <button type="submit" name="action" value="delete"
                    onclick="return confirm('Delete this car?');">Delete</button>
            </div>
        </form>
    </div>
        <a href="index.php" style="
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 8px 16px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    font-family: sans-serif;
    transition: background-color 0.3s ease;
" onmouseover="this.style.backgroundColor='#0056b3'" onmouseout="this.style.backgroundColor='#007BFF'">
            ← Go To Home
        </a>

</body>

</html>