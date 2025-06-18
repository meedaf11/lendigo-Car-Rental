<?php
require_once __DIR__ . '/../functions.php';

$agency_id = $_SESSION['agency_id'] ?? null;

if (!$agency_id) {
    echo "<h3>Agency ID not set.</h3>";
    exit;
}

$bookingsCount = getAgencyBookingsCount($agency_id);
$carsCount = getAgencyCarsCount($agency_id);
$totalEarnings = getAgencyTotalEarnings($agency_id);
$avgRating = getAgencyAverageRating($agency_id);
$statusCounts = getAgencyBookingsByStatus($agency_id, $pdo);
$topCars = getTopRatedCarsByAgency($agency_id, $pdo, 4);
$availabilityCounts = getAgencyCarsAvailability($agency_id, $pdo);
$latestReviews = getLatestAgencyReviews($agency_id, $pdo, 5);
$ratingBreakdown = getAgencyRatingBreakdown($agency_id, $pdo);
$totalReviews = $ratingBreakdown['positive'] + $ratingBreakdown['negative'];
$positivePercent = $totalReviews > 0 ? round(($ratingBreakdown['positive'] / $totalReviews) * 100, 1) : 0;
$negativePercent = $totalReviews > 0 ? round(($ratingBreakdown['negative'] / $totalReviews) * 100, 1) : 0;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/statistics.css">
</head>

<body>
    <div class="content-header">
        <h1 class="content-title">Dashboard Statistics</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $bookingsCount ?></div>
            <div class="stat-label">Total Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $carsCount ?></div>
            <div class="stat-label">Cars Listed</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($totalEarnings, 2) ?> DH</div>
            <div class="stat-label">Current Balence</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $avgRating ?></div>
            <div class="stat-label">Average Rating</div>
        </div>
    </div>

    <!-- Section: Bookings by Status -->
    <div class="content-subsection">
        <h2 class="section-title">📌 Bookings by Status</h2>

        <div class="status-stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $statusCounts['waiting'] ?></div>
                <div class="stat-label">🕒 Waiting</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $statusCounts['reserved'] ?></div>
                <div class="stat-label">✅ Reserved</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $statusCounts['completed'] ?></div>
                <div class="stat-label">🏁 Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $statusCounts['canceled'] ?></div>
                <div class="stat-label">❌ Canceled</div>
            </div>
        </div>
    </div>

    <!-- Section: Top Rated Cars -->
    <div class="content-subsection">
        <h2 class="section-title">⭐ Top Rated Cars</h2>

        <div class="top-cars-grid">
            <?php foreach ($topCars as $car): ?>
                <div class="top-car-card">
                    <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['car_name']) ?>" class="car-image">
                    <div class="car-info">
                        <h3 class="car-name"><?= htmlspecialchars($car['car_name']) ?></h3>
                        <p class="car-rating"><?= number_format($car['avg_rating'], 2) ?>/5</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section: Cars Availability -->
    <div class="content-subsection">
        <h2 class="section-title">🚘 Current car condition</h2>

        <div class="status-stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $availabilityCounts['available'] ?></div>
                <div class="stat-label">🟢 Availlable</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $availabilityCounts['booked'] ?></div>
                <div class="stat-label">🔴 Booked</div>
            </div>
        </div>
    </div>

    <!-- Section: Latest Customer Reviews -->
    <div class="content-subsection">
        <h2 class="section-title">🗣️ Latest customer reviews</h2>

        <div class="reviews-list">
            <?php foreach ($latestReviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= htmlspecialchars($review['full_name']) ?></strong>
                        <span class="review-date"><?= $review['created_at'] ?></span>
                    </div>
                    <div class="review-rating"><?= number_format($review['rating'], 2) ?>/5</div>
                    <p class="review-text">"<?= htmlspecialchars($review['review_text']) ?>"</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section: Rating Distribution -->
    <div class="content-subsection">
        <h2 class="section-title">📊 Customer Reviews</h2>

        <div class="status-stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $ratingBreakdown['positive'] ?> Review</div>
                <div class="stat-label">✅ Positive reviews(<?= $positivePercent ?>%)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $ratingBreakdown['negative'] ?> Review</div>
                <div class="stat-label">❌ negative reviews(<?= $negativePercent ?>%)</div>
            </div>
        </div>
    </div>



</body>

</html>