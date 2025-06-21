<?php

require_once 'config.php';

function loginAdmin($identifier, $password)
{
    global $pdo;

    $query = "SELECT * FROM users 
              WHERE (email = :identifier OR username = :identifier) 
              AND user_type = 'admin' 
              AND status = 'active' 
              LIMIT 1";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['user_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            return true;
        }
    }

    return false;
}


// Total income from payments table
function getTotalIncome()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM payments WHERE payment_status = 'completed'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Total number of agencies
function getTotalAgencies()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM agency");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

// Total number of users
function getTotalUsers()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM users WHERE user_type = 'customer'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

// Total number of cars
function getTotalCars()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function getTotalBookingsByStatus($status)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM booking WHERE status = :status");
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Total active cars
function getTotalActiveCars()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car WHERE status = 'active'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Total booked cars
function getTotalBookedCars()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car WHERE availability_status = 'booked'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Total available cars
function getTotalAvailableCars()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car WHERE availability_status = 'available'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Average daily price of all cars
function getAverageDailyCarPrice()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(price_per_day) AS avg_price FROM car");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return round($row['avg_price'] ?? 0, 2);
}

function getTotalBlockedCars()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car WHERE status = 'blocked'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Total agency reviews
function getTotalAgencyReviews()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM agency_review");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Average agency rating
function getAverageAgencyRating()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating FROM agency_review");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return round($row['avg_rating'] ?? 0, 2);
}

// Total car reviews
function getTotalCarReviews()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM car_review");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Average car rating
function getAverageCarRating()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating FROM car_review");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return round($row['avg_rating'] ?? 0, 2);
}

// Get users with optional status filter
function getUsersWithAgencyCount($status = null)
{
    global $pdo;

    $query = "
        SELECT 
            u.user_id, u.username, u.email, u.phone_number, u.status,
            COUNT(a.agency_id) AS agency_count
        FROM users u
        LEFT JOIN agency a ON u.user_id = a.agency_owner_id
    ";

    if ($status === 'active' || $status === 'blocked') {
        $query .= " WHERE u.status = :status ";
    }

    $query .= " GROUP BY u.user_id";

    $stmt = $pdo->prepare($query);

    if ($status === 'active' || $status === 'blocked') {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update user status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_status'])) {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['new_status'];

    if (in_array($newStatus, ['active', 'blocked'])) {
        $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE user_id = :user_id");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false]);
    exit;
}

// Get total bookings made by user
function getUserTotalBookings($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() ?? 0;
}

// Get booked car details for user
function getUserBookedCars($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.car_name, DATEDIFF(b.end_date, b.start_date) AS days, b.total_price
        FROM booking b
        JOIN car c ON b.car_id = c.car_id
        WHERE b.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all reviews (agency + car) by user
function getUserReviews($user_id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 'Agency' AS type, a.name AS target, a.agency_id AS entity_id, ar.rating, ar.review_text
        FROM agency_review ar
        JOIN agency a ON ar.agency_id = a.agency_id
        WHERE ar.user_id = :uid1
        UNION
        SELECT 'Car', c.car_name AS target, c.car_id AS entity_id, cr.rating, cr.review_text
        FROM car_review cr
        JOIN car c ON cr.car_id = c.car_id
        WHERE cr.user_id = :uid2
    ");
    $stmt->bindParam(':uid1', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':uid2', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Total active agencies
function getActiveAgencies()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM agency WHERE status = 'active'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Total blocked agencies
function getBlockedAgencies()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM agency WHERE status = 'blocked'");
    $stmt->execute();
    return $stmt->fetchColumn();
}


// Total solde (balance) across all agencies
function getTotalAgencySolde()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(solde) FROM agency");
    $stmt->execute();
    return number_format($stmt->fetchColumn(), 2);
}

function getAgencyCities()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT DISTINCT agency_city FROM agency ORDER BY agency_city ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getFilteredAgencies($status = '', $city = '', $search = '')
{
    global $pdo; // بدل conn إلى pdo

    $query = "SELECT a.*, u.username AS owner_name 
              FROM agency a
              JOIN users u ON a.agency_owner_id = u.user_id 
              WHERE 1";

    if ($status === 'active') {
        $query .= " AND a.status = 'active'";
    } elseif ($status === 'blocked') {
        $query .= " AND a.status = 'blocked'";
    }

    if (!empty($city)) {
        $query .= " AND a.agency_city = :city";
    }

    if (!empty($search)) {
        $query .= " AND (a.name LIKE :search OR u.username LIKE :search)";
    }

    $stmt = $pdo->prepare($query); // استخدم $pdo بدلاً من $conn

    if (!empty($city)) {
        $stmt->bindParam(':city', $city);
    }

    if (!empty($search)) {
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAgencyDetails($agencyId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT a.*, u.username, u.email FROM agency a JOIN users u ON a.agency_owner_id = u.user_id WHERE agency_id = ?");
    $stmt->execute([$agencyId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAgencyCars($agencyId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM car WHERE agency_id = ?");
    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyReviews($agencyId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM agency_review WHERE agency_id = ?");
    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyBookings($agencyId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT b.*, u.username, c.car_name 
        FROM booking b 
        JOIN users u ON b.user_id = u.user_id 
        JOIN car c ON b.car_id = c.car_id 
        WHERE c.agency_id = ?
    ");
    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAgencyIncome($agencyId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total_income FROM payments WHERE agency_id = ? AND payment_status = 'completed'");
    $stmt->execute([$agencyId]);
    return $stmt->fetchColumn() ?? 0;
}

function updateAgencyStatus($agencyId, $newStatus) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE agency SET status = :status WHERE agency_id = :agency_id");
    $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':agency_id', $agencyId, PDO::PARAM_INT);
    return $stmt->execute();
}

function getTotalCompletedPayments() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE payment_status = 'completed'");
    $stmt->execute();
    return $stmt->fetchColumn() ?? 0;
}

function getAverageAgencySolde() {
    global $pdo;
    $stmt = $pdo->query("SELECT AVG(solde) FROM agency");
    return $stmt->fetchColumn() ?? 0;
}

function getPlatformRevenue() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT (SELECT SUM(amount) FROM payments WHERE payment_status = 'completed') 
             - (SELECT SUM(solde) FROM agency) AS revenue
    ");
    return $stmt->fetchColumn() ?? 0;
}

function getTopPayingAgency() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT a.name, SUM(p.amount) as total
        FROM payments p
        JOIN agency a ON p.agency_id = a.agency_id
        WHERE p.payment_status = 'completed'
        GROUP BY a.agency_id
        ORDER BY total DESC
        LIMIT 1
    ");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getLowBalanceAgenciesCount() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT COUNT(*) AS low_balance_count FROM (
            SELECT a.agency_id
            FROM agency a
            JOIN car c ON a.agency_id = c.agency_id
            GROUP BY a.agency_id
            HAVING SUM(c.price_per_day) > 0 AND SUM(a.solde) < 0.1 * SUM(c.price_per_day)
        ) AS subquery
    ");
    return $stmt->fetchColumn();
}

// Top 5 agencies by revenue
function getTopRevenueAgencies()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT a.agency_id, a.name, SUM(p.amount) AS total_revenue
        FROM payments p
        JOIN agency a ON p.agency_id = a.agency_id
        WHERE p.payment_status = 'completed'
        GROUP BY a.agency_id
        ORDER BY total_revenue DESC
        LIMIT 5
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Top 5 agencies by average rating
function getTopRatedAgencies()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT a.agency_id, a.name, AVG(r.rating) AS avg_rating
        FROM agency_review r
        JOIN agency a ON r.agency_id = a.agency_id
        GROUP BY a.agency_id
        ORDER BY avg_rating DESC
        LIMIT 5
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Agency with most bookings
function getAgencyWithMostBookings()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT a.agency_id, a.name, COUNT(b.booking_id) AS booking_count
        FROM booking b
        JOIN car c ON b.car_id = c.car_id
        JOIN agency a ON c.agency_id = a.agency_id
        GROUP BY a.agency_id
        ORDER BY booking_count DESC
        LIMIT 3
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Count of agency registrations per city
function getAgencyCountByCity()
{
    global $pdo;
    $stmt = $pdo->query("
        SELECT agency_city, COUNT(*) AS count
        FROM agency
        GROUP BY agency_city
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>