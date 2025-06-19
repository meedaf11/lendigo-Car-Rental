<?php
require_once __DIR__ . '/../functions.php';

$agency_id = $_SESSION['agency_id'] ?? null;
$balance = getAgencyBalance($agency_id, $pdo);
$stats = getAgencyCarPriceStats($agency_id, $pdo);
$payments = getAgencyPayments($agency_id, $pdo);

$requiredMinBalance = round($stats['total_price'] * 0.1, 2);
$missingAmount = $requiredMinBalance - $balance;

if ($missingAmount < 10) {
    $missingAmount = 10;
}


if (isset($_GET['status']) && $_GET['status'] === 'paymentSuccess') {
    echo "<script>alert(' ✅ Payment completed successfully. Your balance has been updated.');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balance</title>
    <link rel="stylesheet" href="css/addBalance.css">
</head>

<body>

    <div class="container">
        <h2 class="page-title">💰 Balance Management</h2>

        <?php if ($balance < $requiredMinBalance): ?>
            <div class="alert-warning">
                A minimum balance of <strong><?= $requiredMinBalance ?> MAD</strong> is required to activate all cars.
                Your current balance is <strong><?= $balance ?> MAD</strong>.
                Please add <strong><?= $missingAmount ?> MAD</strong>.
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Current Balance</h4>
                <p><?= $balance ?> MAD</p>
            </div>
            <div class="stat-card">
                <h4>Number of Cars</h4>
                <p><?= $stats['car_count'] ?></p>
            </div>
            <div class="stat-card">
                <h4>Average Rental Price</h4>
                <p><?= round($stats['avg_price'], 2) ?> MAD</p>
            </div>
        </div>


        <div class="section">
            <h3>Add Balance</h3>
            <form id="balanceForm" action="charge_balance.php" method="POST">
                <div class="form-group">
                    <label for="amount">Amount (MAD):</label>
                    <input type="number" id="amount" name="amount" min="<?= $missingAmount ?>" required
                        placeholder="Enter amount min <?= $missingAmount ?> DH">
                </div>
                <button type="submit" class="btn-primary" id="submitBtn">
                    💳 Pay with Card
                    <span class="loading-spinner" id="loadingSpinner"></span>
                </button>
            </form>
        </div>

        <div class="section">
            <h3>Payment History</h3>
            <table class="payment-history">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $pay): ?>
                            <tr>
                                <td><?= $pay['amount'] ?> MAD</td>
                                <td><?= ucfirst($pay['payment_method']) ?></td>
                                <td><?= ucfirst($pay['payment_status']) ?></td>
                                <td><?= date('Y-m-d H:i', strtotime($pay['payment_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No payment history yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Form submission handling with loading state
        document.getElementById('balanceForm').addEventListener('submit', function (e) {
            const amountInput = document.getElementById('amount');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');

            if (parseFloat(amountInput.value) < <?= $missingAmount ?>) {
                alert("🚫 Please enter at least <?= $missingAmount ?> MAD to activate your cars.");
                e.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            submitBtn.innerHTML = 'Processing... <span class="loading-spinner"></span>';

            setTimeout(() => {
                submitBtn.disabled = false;
                loadingSpinner.style.display = 'none';
                submitBtn.innerHTML = '💳 Pay with Card';
            }, 2000);
        });


        // Add number formatting for amount input
        document.getElementById('amount').addEventListener('input', function (e) {
            let value = e.target.value;
            if (value) {
                // Add visual feedback for valid amounts
                if (parseFloat(value) >= 10) {
                    e.target.style.borderColor = 'var(--success-color)';
                } else {
                    e.target.style.borderColor = 'var(--danger-color)';
                }
            } else {
                e.target.style.borderColor = 'var(--border-light)';
            }
        });

        // Animate table rows on load
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.payment-history tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${1.4 + index * 0.1}s`;
                row.style.animation = 'fadeInUp 0.6s ease-out both';
            });
        });
    </script>

</html>