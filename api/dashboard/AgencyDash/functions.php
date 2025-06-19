<?php
require_once 'config.php';

function getAgencyBookingsCount($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE car_id IN (SELECT car_id FROM car WHERE agency_id = ? AND status = 'active' AND status = 'active' AND status = 'active')");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyCarsCount($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM car WHERE agency_id = ? AND status = 'active' AND status = 'active'");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyTotalEarnings($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE agency_id = ?");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn() ?: 0;
}

function getAgencyAverageRating($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(rating) FROM agency_review WHERE agency_id = ?");
    $stmt->execute([$agency_id]);
    return round($stmt->fetchColumn(), 2) ?: 0;
}

function getAgencyBookingsByStatus($agency_id, $conn)
{
    $sql = "
        SELECT status, COUNT(*) AS count 
        FROM booking 
        WHERE car_id IN (
            SELECT car_id FROM car WHERE agency_id = ? AND status = 'active' AND status = 'active'
        )
        GROUP BY status
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $statusCounts = [
        'waiting' => 0,
        'reserved' => 0,
        'completed' => 0,
        'canceled' => 0
    ];

    foreach ($results as $row) {
        $statusCounts[$row['status']] = $row['count'];
    }

    return $statusCounts;
}

function getTopRatedCarsByAgency($agency_id, $conn, $limit)
{
    $sql = "
        SELECT c.car_id, c.car_name, AVG(r.rating) AS avg_rating, c.image_url
        FROM car_review r
        JOIN car c ON c.car_id = r.car_id
        WHERE c.agency_id = :agencId AND c.status = 'active'
        GROUP BY c.car_id
        ORDER BY avg_rating DESC
        LIMIT :limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":agencId", $agency_id, PDO::PARAM_INT);
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyCarsAvailability($agency_id, $conn)
{
    $sql = "
        SELECT status, availability_status, COUNT(*) AS count 
        FROM car 
        WHERE agency_id = ? 
        GROUP BY status, availability_status
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);

    $availability = [
        'available' => 0,
        'booked' => 0,
        'blocked' => 0
    ];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if ($row['status'] === 'blocked') {
            $availability['blocked'] += $row['count'];
        } else {
            // فقط السيارات النشطة (active) تحسب ضمن "available" أو "booked"
            $availability_status = $row['availability_status'];
            if (isset($availability[$availability_status])) {
                $availability[$availability_status] += $row['count'];
            }
        }
    }

    return $availability;
}


function getLatestAgencyReviews($agency_id, $conn, $limit)
{
    $sql = "
        SELECT r.rating, r.review_text, r.created_at, u.full_name 
        FROM agency_review r
        JOIN users u ON u.user_id = r.user_id
        WHERE r.agency_id = ?
        ORDER BY r.created_at DESC
        LIMIT ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $agency_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyRatingBreakdown($agency_id, $conn)
{
    $sql = "
        SELECT 
            SUM(CASE WHEN rating >= 3 THEN 1 ELSE 0 END) AS positive,
            SUM(CASE WHEN rating < 3 THEN 1 ELSE 0 END) AS negative
        FROM agency_review
        WHERE agency_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'positive' => (int) $result['positive'],
        'negative' => (int) $result['negative'],
    ];
}

function getAgencyCarsWithRatings($agency_id, $conn)
{
    $sql = "
        SELECT 
            c.*, 
            ROUND(AVG(r.rating), 2) AS avg_rating,
            COUNT(r.review_id) AS review_count
        FROM car c
        LEFT JOIN car_review r ON r.car_id = c.car_id
        WHERE c.agency_id = ? AND c.status = 'active'
        GROUP BY c.car_id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyBookings($agency_id, $pdo, $status = null)
{
    $sql = "
        SELECT 
            b.booking_id,
            b.start_date,
            b.end_date,
            b.total_price,
            b.status,
            u.full_name,
            u.email,
            u.phone_number,
            c.car_name,
            c.image_url
        FROM booking b
        JOIN car c ON b.car_id = c.car_id
        JOIN agency a ON c.agency_id = a.agency_id
        JOIN users u ON b.user_id = u.user_id
        WHERE a.agency_id = :agency_id
    ";

    // إضافة شرط الفلترة إذا تم تمرير الحالة
    if (!empty($status)) {
        $sql .= " AND b.status = :status";
    }

    $sql .= " ORDER BY b.booking_id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    if (!empty($status)) {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function updateBookingStatus(PDO $pdo, int $booking_id, string $status): bool
{
    try {
        // تحديث الحالة أولاً
        $stmt = $pdo->prepare("UPDATE booking SET status = :status WHERE booking_id = :booking_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();

        // تنفيذ خصم نسبة المنصة فقط إذا تم التحويل إلى 'reserved'
        if ($status === 'reserved') {
            // استرجاع تفاصيل الحجز
            $sql = "
                SELECT 
                    b.total_price, 
                    DATEDIFF(b.end_date, b.start_date) + 1 AS days, 
                    c.price_per_day, 
                    a.agency_id
                FROM booking b
                JOIN car c ON b.car_id = c.car_id
                JOIN agency a ON c.agency_id = a.agency_id
                WHERE b.booking_id = :booking_id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$booking)
                return false;

            $days = (int) $booking['days'];
            $total_price = (float) $booking['total_price'];
            $daily_price = (float) $booking['price_per_day'];
            $agency_id = (int) $booking['agency_id'];

            // حساب عمولة المنصة
            if ($days <= 3) {
                $platform_fee = 0.10 * $daily_price;
            } else {
                $platform_fee = 0.05 * $total_price;
            }

            // خصم العمولة من رصيد الوكالة
            $updateSolde = $pdo->prepare("UPDATE agency SET solde = solde - :fee WHERE agency_id = :agency_id");
            $updateSolde->bindParam(':fee', $platform_fee);
            $updateSolde->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
            $updateSolde->execute();
        }

        return true;
    } catch (PDOException $e) {
        error_log("Failed to update booking status and deduct fee: " . $e->getMessage());
        return false;
    }
}


function getAgencyReviews($agency_id, PDO $pdo): array
{
    $sql = "
        SELECT r.review_id, r.rating, r.review_text, r.created_at,
               u.full_name
        FROM agency_review r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.agency_id = :agency_id
        ORDER BY r.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyBalance($agency_id, PDO $pdo)
{
    $stmt = $pdo->prepare("SELECT solde FROM agency WHERE agency_id = :agency_id");
    $stmt->execute(['agency_id' => $agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyCarPriceStats($agency_id, PDO $pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) as car_count, SUM(price_per_day) as total_price, AVG(price_per_day) as avg_price
                           FROM car WHERE agency_id = :agency_id");
    $stmt->execute(['agency_id' => $agency_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAgencyPayments($agency_id, PDO $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM payments WHERE agency_id = :agency_id ORDER BY payment_date DESC");
    $stmt->execute(['agency_id' => $agency_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPayment($agency_id, $amount, $method, $status, PDO $pdo)
{
    $stmt = $pdo->prepare("INSERT INTO payments (agency_id, amount, payment_method, payment_status) 
                           VALUES (:agency_id, :amount, :method, :status)");
    $stmt->execute([
        'agency_id' => $agency_id,
        'amount' => $amount,
        'method' => $method,
        'status' => $status
    ]);
}

function getAgencyById(int $agency_id, PDO $pdo): ?array
{
    $sql = "SELECT * FROM agency WHERE agency_id = :agency_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    $stmt->execute();
    $agency = $stmt->fetch(PDO::FETCH_ASSOC);
    return $agency ?: null;
}

function deleteAgencyById(int $agency_id, PDO $pdo): bool
{
    $sql = "DELETE FROM agency WHERE agency_id = :agency_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function updateAgency(array $data, PDO $pdo): bool
{
    $sql = "UPDATE agency 
            SET name = :name, 
                description = :description,
                image = :image,
                contact_email = :contact_email,
                phone_number = :phone_number,
                agency_city = :agency_city,
                location = :location
            WHERE agency_id = :agency_id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':image', $data['image']);
    $stmt->bindParam(':contact_email', $data['contact_email']);
    $stmt->bindParam(':phone_number', $data['phone_number']);
    $stmt->bindParam(':agency_city', $data['agency_city']);
    $stmt->bindParam(':location', $data['location']);
    $stmt->bindParam(':agency_id', $data['agency_id'], PDO::PARAM_INT);

    $success = $stmt->execute();

    if (!$success) {
        $error = $stmt->errorInfo();
        error_log("❌ فشل تحديث الوكالة: " . $error[2]);
    }

    return $success;
}
