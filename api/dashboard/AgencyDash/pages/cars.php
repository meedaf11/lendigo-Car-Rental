<?php
require_once __DIR__ . '/../functions.php';

$agency_id = $_SESSION['agency_id'] ?? null;

if (!$agency_id) {
    echo "<h3>Agency ID not set.</h3>";
    exit;
}



$cars = getAgencyCarsWithRatings($agency_id, $pdo);

// Handle selected car from URL
$selectedCar = null;
$selectedCarId = $_GET['car_id'] ?? null;

if (isset($_GET['status']) && $_GET['status'] === 'car_added'){
    echo "<script>alert('✅ Car added successfully!');</script>";
}

if ($selectedCarId) {
    foreach ($cars as $car) {
        if ($car['car_id'] == $selectedCarId) {
            $selectedCar = $car;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agency Cars</title>
    <link rel="stylesheet" href="css/cars.css">
</head>

<body>

    <div class="cars-page">
        <h2 class="page-title">🚗 Car List</h2>
        <a href="add_car.php" class="add-car-button"> Add New Car</a>

        <div class="cars-list">
            <?php foreach ($cars as $car): ?>
                <div class="car-card" data-id="<?= $car['car_id'] ?>" data-name="<?= htmlspecialchars($car['car_name']) ?>"
                    data-img="<?= htmlspecialchars($car['image_url']) ?>"
                    data-brand="<?= htmlspecialchars($car['brand']) ?>" data-model="<?= htmlspecialchars($car['model']) ?>"
                    data-fuel="<?= htmlspecialchars($car['car_fuel']) ?>" data-places="<?= $car['places'] ?>"
                    data-price="<?= $car['price_per_day'] ?>"
                    data-auto="<?= $car['isAutomatic'] ? 'Automatic' : 'Manual' ?>"
                    data-rating="<?= $car['avg_rating'] ?? 'No rating' ?>" data-status="<?= $car['availability_status'] ?>"
                    data-desc="<?= htmlspecialchars($car['description']) ?>">

                    <span class="car-status <?= $car['availability_status'] ?>">
                        <?= ucfirst($car['availability_status']) ?>
                    </span>
                    <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['car_name']) ?>">
                    <div class="car-name"><?= htmlspecialchars($car['car_name']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="carModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">×</span>
            <div id="modal-body"></div>
        </div>
    </div>





    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.car-card').forEach(card => {
                card.addEventListener('click', () => {
                    const carId = card.getAttribute('data-id');
                    window.location.href = `edit_car.php?car_id=${carId}`;
                });
            });
        });
    </script>


</body>

</html>