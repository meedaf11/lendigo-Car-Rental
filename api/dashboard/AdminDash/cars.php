<?php
require_once 'functions.php';

// Handle GET parameters
$selectedPrice = $_GET['price'] ?? '';
$selectedStatus = $_GET['status'] ?? '';
$searchName = $_GET['name'] ?? '';
$ratingOrder = $_GET['rating_order'] ?? '';

// Fetch statistics
$totalCars = getTotalCarsCount();
$activeCars = getActiveCarsCount();
$blockedCars = getUnavailableCarsCount();

// Get filtered car list
$cars = getFilteredCars($searchName, $selectedPrice, $selectedStatus, $ratingOrder);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cars Overview</title>
    <link rel="stylesheet" href="css/cars.css" />
</head>

<div class="carContent">

    <!-- Stats Section -->
    <div class="car-stats-container">
        <div class="stat-card">
            <h4>Total Cars</h4>
            <p><?= $totalCars ?></p>
        </div>
        <div class="stat-card">
            <h4>Active Cars</h4>
            <p><?= $activeCars ?></p>
        </div>
        <div class="stat-card">
            <h4>Unavailable Cars</h4>
            <p><?= $blockedCars ?></p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <input type="hidden" name="page" value="cars" />

            <div class="filter-group">
                <label for="statusFilter">Car Status</label>
                <select name="status" id="statusFilter" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="active" <?= $selectedStatus === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="blocked" <?= $selectedStatus === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="priceFilter">Price Range</label>
                <select name="price" id="priceFilter" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="lt300" <?= $selectedPrice === 'lt300' ? 'selected' : '' ?>>Less than 300 DH</option>
                    <option value="300to300" <?= $selectedPrice === '300to300' ? 'selected' : '' ?>>300–500 DH</option>
                    <option value="gt300" <?= $selectedPrice === 'gt300' ? 'selected' : '' ?>>More than 500 DH</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="ratingOrder">Sort by Rating</label>
                <select name="rating_order" id="ratingOrder" onchange="this.form.submit()">
                    <option value="">Default</option>
                    <option value="asc" <?= $ratingOrder === 'asc' ? 'selected' : '' ?>>Low → High</option>
                    <option value="desc" <?= $ratingOrder === 'desc' ? 'selected' : '' ?>>High → Low</option>
                </select>
            </div>


            <div class="filter-group search-box">
                <label for="carName">Search by Name</label>
                <input type="text" id="carName" name="name" placeholder="e.g. Toyota Corolla"
                    value="<?= htmlspecialchars($searchName) ?>" />
                <button type="submit">🔍</button>
            </div>
        </form>
    </div>

    <!-- Cars Table -->
    <div class="cars-table-container">
        <table class="cars-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car Name & Model</th>
                    <th>Brand</th>
                    <th>Agency</th>
                    <th>Price/Day</th>
                    <th>Bookings</th>
                    <th>Avg. Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($cars) > 0): ?>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?= $car['car_id'] ?></td>
                            <td><?= htmlspecialchars($car['car_name'] . ' ' . $car['model']) ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['agency_name']) ?></td>
                            <td><?= number_format($car['price_per_day'], 2) ?> DH</td>
                            <td><?= $car['booking_count'] ?></td>
                            <td><?= number_format($car['avg_rating'], 1) ?>★</td>
                            <td><?= $car['status'] === 'active' ? '✅ Active' : '🚫 Blocked' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">No cars found matching your filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>








</div>

<div id="carModal" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn" onclick="closeCarModal()">×</span>
        <div id="carDetails">Loading...</div>
    </div>
</div>
<script>
    document.querySelectorAll('.cars-table tbody tr').forEach(row => {
        row.addEventListener('click', function () {
            const carId = this.querySelector('td').textContent;
            fetch(`get_car_details.php?car_id=${carId}`)
                .then(res => res.json())
                .then(data => showCarModal(data));
        });
    });

    function showCarModal(data) {
        const modal = document.getElementById("carModal");
        const content = document.getElementById("carDetails");
        const car = data.car;
        const reviews = data.reviews;
        const bookings = data.bookings;

        let html = `
        <h2>${car.car_name} (${car.model})</h2>
        <p><strong>Brand:</strong> ${car.brand}</p>
        <p><strong>Agency:</strong> ${car.agency_name}</p>
        <p><strong>Price/Day:</strong> ${parseFloat(car.price_per_day).toFixed(2)} DH</p>
        <p><strong>Status:</strong> ${car.status === 'active' ? '✅ Active' : '🚫 Blocked'}</p>

        <h3>📚 Bookings</h3>
        <ul>
            ${bookings.length > 0 ? bookings.map(b => `
                <li>
                    ${b.full_name} — ${b.start_date} to ${b.end_date} 
                    (${b.total_days} days) → <strong>${parseFloat(b.total_price).toFixed(2)} DH</strong>
                    <em>Status: ${b.status}</em>
                </li>
            `).join('') : '<li>No bookings available.</li>'}
        </ul>

        <h3>🌟 Reviews</h3>
        <ul>
            ${reviews.length > 0 ? reviews.map(r => `
                <li>
                    ${r.username} — ${r.rating}★<br>${r.review_text}
                </li>
            `).join('') : '<li>No reviews available.</li>'}
        </ul>

        <h3>🚦 Change Car Status</h3>
        <form id="carStatusForm">
            <input type="hidden" name="car_id" value="${car.car_id}">
            <select name="new_status" required>
                <option value="active" ${car.status === 'active' ? 'selected' : ''}>Active</option>
                <option value="blocked" ${car.status === 'blocked' ? 'selected' : ''}>Blocked</option>
            </select>
            <button type="submit">Update</button>
        </form>
        <div id="carStatusMsg" style="margin-top: 5px;"></div>
    `;

        content.innerHTML = html;

        // Show modal and scroll up
        modal.classList.remove("hidden");
        document.body.style.overflow = 'hidden';
        window.scrollTo({ top: 0, behavior: 'smooth' }); // ✅ Scroll to top smoothly
    }

    document.addEventListener('submit', function (e) {
        if (e.target && e.target.id === 'carStatusForm') {
            e.preventDefault();

            const form = e.target;
            const carId = form.car_id.value;
            const newStatus = form.new_status.value;

            fetch('update_car_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ car_id: carId, new_status: newStatus })
            })
                .then(res => res.json())
                .then(response => {
                    const msg = document.getElementById('carStatusMsg');
                    if (response.success) {
                        msg.innerText = '✅ Status updated!';
                        msg.style.color = 'green';
                    } else {
                        msg.innerText = '❌ Update failed.';
                        msg.style.color = 'red';
                    }
                })
                .catch(() => {
                    const msg = document.getElementById('carStatusMsg');
                    msg.innerText = '❗ Error while updating status.';
                    msg.style.color = 'red';
                });
        }
    });



    function closeCarModal() {
        const modal = document.getElementById('carModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

</html>