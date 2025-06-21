<?php
require_once __DIR__ . '/../functions.php';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {

    $booking_id = (int) $_POST['booking_id'];
    $status = $_POST['status'];

    updateBookingStatus($pdo, $booking_id, $status);
}

$agency_id = $_SESSION['agency_id'] ?? null;
$statusFilter = $_GET['status'] ?? null;
$bookings = getAgencyBookings($agency_id, $pdo, $statusFilter);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agency Bookings</title>
    <link rel="stylesheet" href="css/booking.css">
</head>

<body>

    <h2 class="page-title">📅 Bookings</h2>

    <<form method="GET" class="filter-form">
        <input type="hidden" name="page" value="bookings">
        <label for="filterStatus"><strong>Filter by Status:</strong></label>
        <select name="status" id="filterStatus">
            <option value="">All</option>
            <option value="waiting" <?= ($_GET['status'] ?? '') === 'waiting' ? 'selected' : '' ?>>Waiting</option>
            <option value="reserved" <?= ($_GET['status'] ?? '') === 'reserved' ? 'selected' : '' ?>>Reserved</option>
            <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="canceled" <?= ($_GET['status'] ?? '') === 'canceled' ? 'selected' : '' ?>>Canceled</option>
        </select>
        <button type="submit">Filter</button>
        </form>

        <div class="bookings-list">
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $b): ?>
                    <?php
                    $data = htmlspecialchars(json_encode([
                        "id" => $b["booking_id"],
                        "status" => $b["status"],
                        "car" => $b["car_name"],
                        "start" => $b["start_date"],
                        "end" => $b["end_date"],
                        "total" => $b["total_price"],
                        "customer" => $b["full_name"],
                        "email" => $b["email"],
                        "phone" => $b["phone_number"]
                    ]), ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="booking-card" data-booking='<?= $data ?>'>
                        <img src="<?= htmlspecialchars($b['image_url']) ?>" alt="<?= htmlspecialchars($b['car_name']) ?>">
                        <div class="booking-info">
                            <h3><?= htmlspecialchars($b['car_name']) ?></h3>
                            <p><strong>Customer:</strong> <?= htmlspecialchars($b['full_name']) ?></p>
                            <p><strong>From:</strong> <?= $b['start_date'] ?> → <strong>To:</strong> <?= $b['end_date'] ?></p>
                            <p><strong>Total:</strong> <?= $b['total_price'] ?> DH</p>
                            <p class="status-<?= strtolower($b['status']) ?>">
                                <strong>Status:</strong> <?= ucfirst($b['status']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>

        <!-- Modal -->
        <div id="bookingModal" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="flex-direction: column; max-width: 500px;">
                <span class="close-modal" onclick="closeBookingModal()">&times;</span>
                <h3 style="margin-bottom: 12px;">🧾 Booking Details</h3>
                <div id="bookingDetails" style="display: flex; flex-direction: column; gap: 10px;">
                    <p><strong>Customer:</strong> <span id="modalCustomer"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                    <p><strong>Car:</strong> <span id="modalCar"></span></p>
                    <p><strong>Period:</strong> <span id="modalPeriod"></span></p>
                    <p><strong>Total:</strong> <span id="modalTotal"></span></p>
                    <form method="POST">
                        <input type="hidden" name="booking_id" id="modalBookingId">
                        <label for="modalStatus"><strong>Change Status:</strong></label>
                        <select name="status" id="modalStatus">
                            <option value="waiting">Waiting</option>
                            <option value="reserved">Reserved</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                        <button type="submit"
                            style="margin-top: 12px; padding: 8px 14px; background: var(--primary-color); color: white; border: none; border-radius: 6px;">💾
                            Update</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openBookingModal(data) {
                // الحساب
                const start = new Date(data.start);
                const end = new Date(data.end);
                const days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                const dailyPrice = parseFloat(data.total) / days;
                let fee = 0;

                let message = "";
                if (days <= 3) {
                    fee = 0.10 * dailyPrice;
                    message = `📌 Platform takes 10% of 1-day price (${dailyPrice.toFixed(2)} DH) = ${fee.toFixed(2)} DH`;
                } else {
                    fee = 0.05 * parseFloat(data.total);
                    message = `📌 Platform takes 5% of total price (${data.total} DH) = ${fee.toFixed(2)} DH`;
                }

                if (data.status === "canceled") {
                    document.getElementById('modalCustomer').textContent = "Hidden";
                    document.getElementById('modalEmail').textContent = "Hidden";
                    document.getElementById('modalPhone').textContent = "Hidden";
                } else {
                    document.getElementById('modalCustomer').textContent = data.customer;
                    document.getElementById('modalEmail').textContent = data.email;
                    document.getElementById('modalPhone').textContent = data.phone;
                }

                document.getElementById('modalBookingId').value = data.id;
                document.getElementById('modalCar').textContent = data.car;
                document.getElementById('modalPeriod').textContent = `${data.start} → ${data.end}`;
                document.getElementById('modalTotal').textContent = `${data.total} DH`;
                document.getElementById('modalStatus').value = data.status;

                // إضافة الرسالة
                const feeNote = document.createElement('p');
                feeNote.style.marginTop = "10px";
                feeNote.style.color = "green";
                feeNote.innerHTML = `<strong>Platform Fee:</strong> ${message}`;
                const detailsDiv = document.getElementById('bookingDetails');
                // إزالة القديم إذا وُجد
                const old = document.getElementById('platformFeeNote');
                if (old) old.remove();
                feeNote.id = "platformFeeNote";
                detailsDiv.insertBefore(feeNote, detailsDiv.querySelector("form"));

                document.getElementById('bookingModal').style.display = 'flex';
            }


            function closeBookingModal() {
                document.getElementById('bookingModal').style.display = 'none';
            }

            // Attach click event safely
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('.booking-card').forEach(card => {
                    card.addEventListener('click', () => {
                        const data = JSON.parse(card.getAttribute('data-booking'));
                        openBookingModal(data);
                    });
                });
            });
        </script>

</body>

</html>