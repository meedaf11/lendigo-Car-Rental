<?php
require_once 'config.php'; // تأكد أنه يحتوي على session_start و$pdo

function getAgencyBookingsCount($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE car_id IN (SELECT car_id FROM car WHERE agency_id = ?)");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyCarsCount($agency_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM car WHERE agency_id = ?");
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
            SELECT car_id FROM car WHERE agency_id = ?
        )
        GROUP BY status
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // نحول النتائج إلى associative array مثل:
    // ['waiting' => 5, 'completed' => 3]
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
        WHERE c.agency_id = :agencId
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
        SELECT availability_status, COUNT(*) AS count 
        FROM car 
        WHERE agency_id = ? 
        GROUP BY availability_status
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);

    $availability = [
        'available' => 0,
        'booked' => 0
    ];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $availability[$row['availability_status']] = $row['count'];
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
        'positive' => (int)$result['positive'],
        'negative' => (int)$result['negative'],
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
        WHERE c.agency_id = ?
        GROUP BY c.car_id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$agency_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
