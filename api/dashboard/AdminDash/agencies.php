<?php
require_once 'functions.php';

$totalAgencies = getTotalAgencies();
$activeAgencies = getActiveAgencies();
$blockedAgencies = getBlockedAgencies();
$averageRating = getAverageAgencyRating();
$totalSolde = getTotalAgencySolde();
$cityOptions = getAgencyCities();
$selectedStatus = $_GET['status'] ?? '';
$selectedCity = $_GET['city'] ?? '';
$searchTerm = $_GET['search'] ?? '';

$totalPayments = getTotalCompletedPayments();
$avgSolde = getAverageAgencySolde();
$platformRevenue = getPlatformRevenue();
$topAgency = getTopPayingAgency();
$lowBalanceCount = getLowBalanceAgenciesCount();

$topRevenueAgencies = getTopRevenueAgencies();
$topRatedAgencies = getTopRatedAgencies();
$mostBookedAgencies = getAgencyWithMostBookings();
$cityAgencyCounts = getAgencyCountByCity();

$agencies = getFilteredAgencies($selectedStatus, $selectedCity, $searchTerm);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencies Management</title>
    <link rel="stylesheet" href="css/agencies.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div class="Container">

    <div class="header">
        <h1>Agency Overview</h1>
        <p>Real-time insights into your agency performance and metrics</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-icon">🏢</div>
            <div class="stat-title">Total Agencies</div>
            <div class="stat-value number-animation">
                <span id="totalAgencies"><?= $totalAgencies ?></span>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon">✅</div>
            <div class="stat-title">Active Agencies</div>
            <div class="stat-value number-animation">
                <span id="activeAgencies"><?= $activeAgencies ?></span>
            </div>
        </div>

        <div class="stat-card blocked">
            <div class="stat-icon">🚫</div>
            <div class="stat-title">Blocked Agencies</div>
            <div class="stat-value number-animation">
                <span id="blockedAgencies"><?= $blockedAgencies ?></span>
            </div>
        </div>

        <div class="stat-card rating">
            <div class="stat-icon">⭐</div>
            <div class="stat-title">Average Rating</div>
            <div class="stat-value number-animation">
                <span id="averageRating"><?= $averageRating ?></span>
            </div>
        </div>

        <div class="stat-card balance">
            <div class="stat-icon">💰</div>
            <div class="stat-title">Total Balance</div>
            <div class="stat-value number-animation">
                <span id="totalBalance"><?= $totalSolde ?></span>
            </div>
        </div>
    </div>
</div>

<div class="filters-container">
    <form method="GET" class="filter-form">
        <input type="hidden" name="page" value="agencies">
        <div class="filter-group">
            <label>Status:</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="active" <?= $selectedStatus === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="blocked" <?= $selectedStatus === 'blocked' ? 'selected' : '' ?>>Blocked</option>
            </select>
        </div>

        <div class="filter-group">
            <label>City:</label>
            <select name="city" onchange="this.form.submit()">
                <option value="">All</option>
                <?php foreach ($cityOptions as $city): ?>
                    <option value="<?= htmlspecialchars($city) ?>" <?= $selectedCity === $city ? 'selected' : '' ?>>
                        <?= htmlspecialchars($city) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group search-box">
            <input type="text" name="search" placeholder="Search by agency or owner..."
                value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">🔍</button>
        </div>
    </form>
</div>

<div class="agency-table-container">
    <table class="agency-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Agency Name</th>
                <th>Owner</th>
                <th>City</th>
                <th>Rating</th>
                <th>Solde</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($agencies) > 0): ?>
                <?php foreach ($agencies as $agency): ?>
                    <tr class="agency-row" data-agency-id="<?= $agency['agency_id'] ?>">
                        <td><?= $agency['agency_id'] ?></td>
                        <td><?= htmlspecialchars($agency['name']) ?></td>
                        <td><?= htmlspecialchars($agency['owner_name']) ?></td>
                        <td><?= htmlspecialchars($agency['agency_city']) ?></td>
                        <td><?= number_format($agency['rating'], 1) ?>★</td>
                        <td><?= number_format($agency['solde'], 2) ?> DH</td>
                        <td>
                            <?= $agency['status'] === 'active' ? '✅ Active' : '🚫 Blocked' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No agencies found matching your filters.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



<div class="fin-stats-grid">
    <h2>Financial Summary</h2>
    <div class="fin-stat-card balance">
        <div class="fin-stat-icon">💳</div>
        <div class="fin-stat-title">Total Payments</div>
        <div class="fin-stat-value"><?= number_format($totalPayments, 2) ?> MAD</div>
    </div>
    <div class="fin-stat-card active">
        <div class="fin-stat-icon">📊</div>
        <div class="fin-stat-title">Avg. Solde</div>
        <div class="fin-stat-value"><?= number_format($avgSolde, 2) ?> MAD</div>
    </div>
    <div class="fin-stat-card total">
        <div class="fin-stat-icon">📈</div>
        <div class="fin-stat-title">Platform Revenue</div>
        <div class="fin-stat-value"><?= number_format($platformRevenue, 2) ?> MAD</div>
    </div>
    <div class="fin-stat-card rating">
        <div class="fin-stat-icon">🏆</div>
        <div class="fin-stat-title">Top Agency</div>
        <div class="fin-stat-value"><?= htmlspecialchars($topAgency['name']) ?> -
            <?= number_format($topAgency['total'], 2) ?> MAD
        </div>
    </div>
    <div class="fin-stat-card blocked">
        <div class="fin-stat-icon">⚠️</div>
        <div class="fin-stat-title">Low Balance</div>
        <div class="fin-stat-value"><?= $lowBalanceCount ?> agencies</div>
    </div>
</div>


<div class="performance-insights" style="margin-top: 3rem;">
    <h2>Agency Performance Insights</h2>

    <div class="insight-section">
        <h3>🔝 Top 5 Agencies by Revenue</h3>
        <ul>
            <?php foreach ($topRevenueAgencies as $agency): ?>
                <li>
                    <?= htmlspecialchars($agency['name']) ?> (ID: <?= $agency['agency_id'] ?>)
                    — <strong><?= number_format($agency['total_revenue'], 2) ?> MAD</strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="insight-section">
        <h3>🌟 Top 5 Agencies by Rating</h3>
        <ul>
            <?php foreach ($topRatedAgencies as $agency): ?>
                <li>
                    <?= htmlspecialchars($agency['name']) ?> (ID: <?= $agency['agency_id'] ?>)
                    — <strong><?= number_format($agency['avg_rating'], 1) ?>★</strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="insight-section">
        <h3>🏆 Top 3 Most Booked Agencies</h3>
        <ul>
            <?php foreach ($mostBookedAgencies as $agency): ?>
                <p>
                    <?= htmlspecialchars($agency['name']) ?> (ID: <?= $agency['agency_id'] ?>)
                    — <strong><?= $agency['booking_count'] ?> Bookings</strong>
                </p>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="insight-section">
        <h3>🗺️ Agency Registrations by City</h3>
        <canvas id="cityHeatmap" height="250"></canvas>
    </div>
</div>





<div id="agencyModal" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="agencyDetails">Loading...</div>
    </div>
</div>

<script src="js/agencies.js"></script>
<script>
    const ctx = document.getElementById('cityHeatmap').getContext('2d');
    const cityLabels = <?= json_encode(array_column($cityAgencyCounts, 'agency_city')) ?>;
    const cityCounts = <?= json_encode(array_column($cityAgencyCounts, 'count')) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: cityLabels,
            datasets: [{
                label: 'Agency Count',
                data: cityCounts,
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Number of Agencies by City'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>

</html>