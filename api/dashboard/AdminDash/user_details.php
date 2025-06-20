<?php
require_once 'functions.php';

if (!isset($_GET['user_id']))
    exit;

$user_id = (int) $_GET['user_id'];

$totalBookings = getUserTotalBookings($user_id);
$bookings = getUserBookedCars($user_id);
$reviews = getUserReviews($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user_details.css">
</head>

<body>

    <div class="user-details-container">
        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stats-icon">📊</div>
            <div class="stats-content">
                <h3>Total Bookings</h3>
                <p><?= $totalBookings ?> bookings completed</p>
            </div>
        </div>

        <!-- Booked Cars Section -->
        <div class="section">
            <div class="section-header">
                <div class="section-icon">🚗</div>
                <h4 class="section-title">Booked Cars</h4>
            </div>

            <?php if ($bookings): ?>
                <ul class="bookings-list">
                    <?php foreach ($bookings as $b): ?>
                        <li class="booking-item">
                            <div class="booking-header">
                                <h5 class="car-name"><?= htmlspecialchars($b['car_name']) ?></h5>
                                <span class="booking-price"><?= number_format($b['total_price'], 2) ?> MAD</span>
                            </div>
                            <div class="booking-duration"><?= $b['days'] ?> days rental period</div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🚫</div>
                    <div>No bookings found.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Reviews Section -->
        <div class="section">
            <div class="section-header">
                <div class="section-icon">⭐</div>
                <h4 class="section-title">User Reviews</h4>
            </div>

            <?php if ($reviews): ?>
                <ul class="reviews-list">
                    <?php foreach ($reviews as $r): ?>
                        <li class="review-item">
                            <div class="review-header">
                                <div class="review-type-target">
                                    <span class="review-type"><?= $r['type'] ?></span>
                                    <span class="review-target">
                                        <?= htmlspecialchars($r['target']) ?>
                                        <?php if (isset($r['entity_id'])): ?>
                                            <span style="color: var(--text-muted); font-size: 13px;">
                                                (ID: <?= $r['entity_id'] ?>)
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="review-rating">
                                    <?= $r['rating'] ?>★
                                </div>
                            </div>
                            <div class="review-text">
                                <?= htmlspecialchars($r['review_text']) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <div>No reviews found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>