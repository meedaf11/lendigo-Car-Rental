<?php
require_once 'functions.php';

$agencyId = $_GET['agency_id'] ?? null;
if (!$agencyId) {
    echo "Invalid agency ID";
    exit;
}

$agency = getAgencyDetails($agencyId);
if (!$agency) {
    echo "Agency not found";
    exit;
}

$cars = getAgencyCars($agencyId);
$reviews = getAgencyReviews($agencyId);
$bookings = getAgencyBookings($agencyId);
$income = getAgencyIncome($agencyId);

$currentSolde = $agency['solde'];
$platformEarnings = $income - $currentSolde;


?>

<h2>Agency: <?= htmlspecialchars($agency['name']) ?> (ID: <?= $agency['agency_id'] ?>)</h2>
<p><strong>Owner:</strong> <?= htmlspecialchars($agency['username']) ?> (<?= htmlspecialchars($agency['email']) ?>)</p>
<p><strong>City:</strong> <?= htmlspecialchars($agency['agency_city']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($agency['status']) ?></p>
<p><strong>Solde:</strong> <?= number_format($currentSolde, 2) ?> MAD</p>
<p><strong>Total Payments:</strong> <?= number_format($income, 2) ?> MAD</p>
<p><strong>Platform Earnings:</strong> <?= number_format($platformEarnings, 2) ?> MAD</p>

<h3>Cars (<?= count($cars) ?>)</h3>
<ul>
    <?php foreach ($cars as $car): ?>
        <li><?= htmlspecialchars($car['car_name']) ?> - <?= $car['availability_status'] ?> (<?= $car['status'] ?>)</li>
    <?php endforeach; ?>
</ul>

<h3>Bookings</h3>
<ul>
    <?php foreach ($bookings as $b): ?>
        <li><?= htmlspecialchars($b['username']) ?> reserved <?= htmlspecialchars($b['car_name']) ?>
            from <?= $b['start_date'] ?> to <?= $b['end_date'] ?> - <strong><?= $b['status'] ?></strong></li>
    <?php endforeach; ?>
</ul>

<h3>Reviews</h3>
<ul>
    <?php foreach ($reviews as $r): ?>
        <li><strong><?= $r['rating'] ?>★</strong> — <?= htmlspecialchars($r['review_text']) ?></li>
    <?php endforeach; ?>

    <h3>Change Agency Status</h3>
    <form method="POST" action="update_agency_status.php">
        <input type="hidden" name="agency_id" value="<?= $agency['agency_id'] ?>">
        <select name="new_status" required>
            <option value="active" <?= $agency['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="blocked" <?= $agency['status'] === 'blocked' ? 'selected' : '' ?>>Blocked</option>
        </select>
        <button type="submit">Update Status</button>
    </form>

</ul>