<?php
require_once __DIR__ . '/../functions.php';
$agency_id = $_SESSION['agency_id'] ?? null;
$reviews = getAgencyReviews($agency_id, $pdo);

// Calculate total and average
$totalReviews = count($reviews);
$averageRating = $totalReviews > 0
    ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1)
    : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agency Reviews</title>
    <link rel="stylesheet" href="css/reviews.css">
</head>

<body>

<h2 class="page-title">⭐ Reviews</h2>

<!-- Stat Cards -->
<div class="review-stats">
    <div class="stat-card">
        <h3>Total Reviews</h3>
        <p><?= $totalReviews ?></p>
    </div>
    <div class="stat-card">
        <h3>Average Rating</h3>
        <p>⭐ <?= $averageRating ?>/5</p>
    </div>
</div>

<!-- Reviews List -->
<div class="reviews-list">
    <?php if ($totalReviews > 0): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-content">
                    <p><strong><?= htmlspecialchars($review['full_name']) ?></strong></p>
                    <p><?= htmlspecialchars($review['review_text']) ?></p>
                    <p>⭐ <?= $review['rating'] ?>/5</p>
                    <small><?= date('F j, Y', strtotime($review['created_at'])) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews found.</p>
    <?php endif; ?>
</div>

</body>
</html>
