<?php
require_once 'functions.php';

$totalIncome = getTotalIncome();
$totalAgencies = getTotalAgencies();
$totalUsers = getTotalUsers();
$totalCars = getTotalCars();

$waiting = getTotalBookingsByStatus('waiting');
$reserved = getTotalBookingsByStatus('reserved');
$completed = getTotalBookingsByStatus('completed');
$canceled = getTotalBookingsByStatus('canceled');

$activeCars = getTotalActiveCars();
$bookedCars = getTotalBookedCars();
$availableCars = getTotalAvailableCars();
$averagePrice = getAverageDailyCarPrice();
$blockedCars = getTotalBlockedCars();

$totalAgencyReviews = getTotalAgencyReviews();
$avgAgencyRating = getAverageAgencyRating();
$totalCarReviews = getTotalCarReviews();
$avgCarRating = getAverageCarRating();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Statistics</title>
    <link rel="stylesheet" href="css/statistics.css">

</head>

<body>

    <div class="cards">
        <div class="card">
            <h3 class="card-title">Total Income</h3>
            <p class="card-value"><?= number_format($totalIncome, 2) ?> MAD</p>
        </div>
        <div class="card">
            <h3 class="card-title">Total Agencies</h3>
            <p class="card-value"><?= $totalAgencies ?></p>
        </div>
        <div class="card">
            <h3 class="card-title">Total Users</h3>
            <p class="card-value"><?= $totalUsers ?></p>
        </div>
        <div class="card">
            <h3 class="card-title">Total Cars</h3>
            <p class="card-value"><?= $totalCars ?></p>
        </div>
    </div>



    <div class="cards">
        <h2 class="stats-header">Booking Status Summary</h2>
        <div class="card">
            <div class="card-title">Waiting</div>
            <div class="card-value"><?= $waiting ?></div>
        </div>
        <div class="card">
            <div class="card-title">Reserved</div>
            <div class="card-value"><?= $reserved ?></div>
        </div>
        <div class="card">
            <div class="card-title">Completed</div>
            <div class="card-value"><?= $completed ?></div>
        </div>
        <div class="card">
            <div class="card-title">Canceled</div>
            <div class="card-value"><?= $canceled ?></div>
        </div>
    </div>



    <div class="cards">
        <h2 class="stats-header">Car Insights</h2>
        <div class="card">
            <div class="card-title">Active Cars</div>
            <div class="card-value"><?= $activeCars ?></div>
        </div>
        <div class="card">
            <div class="card-title">Blocked Cars</div>
            <div class="card-value"><?= $blockedCars ?></div>
        </div>
        <div class="card">
            <div class="card-title">Booked Cars</div>
            <div class="card-value"><?= $bookedCars ?></div>
        </div>
        <div class="card">
            <div class="card-title">Available Cars</div>
            <div class="card-value"><?= $availableCars ?></div>
        </div>
        <div class="card">
            <div class="card-title">Avg Daily Price</div>
            <div class="card-value"><?= number_format($averagePrice, 2) ?> MAD</div>
        </div>
    </div>



    <div class="cards">
        <h2 class="stats-header">Review Statistics</h2>
        <div class="card">
            <div class="card-title">Total Agency Reviews</div>
            <div class="card-value"><?= $totalAgencyReviews ?></div>
        </div>
        <div class="card">
            <div class="card-title">Avg Agency Rating</div>
            <div class="card-value"><?= $avgAgencyRating ?> ★</div>
        </div>
        <div class="card">
            <div class="card-title">Total Car Reviews</div>
            <div class="card-value"><?= $totalCarReviews ?></div>
        </div>
        <div class="card">
            <div class="card-title">Avg Car Rating</div>
            <div class="card-value"><?= $avgCarRating ?> ★</div>
        </div>
    </div>


</body>

</html>