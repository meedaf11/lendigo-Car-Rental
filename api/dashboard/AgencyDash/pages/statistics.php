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
        <div class="stat-icon">📅</div>
        <div class="stat-value"><?= $bookingsCount ?></div>
        <div class="stat-label">Total Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🚗</div>
        <div class="stat-value"><?= $carsCount ?></div>
        <div class="stat-label">Cars Listed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value"><?= number_format($totalEarnings, 2) ?> DH</div>
        <div class="stat-label">Current Balence</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value"><?= $avgRating ?></div>
        <div class="stat-label">Average Rating</div>
    </div>
</div>
</body>
</html>
