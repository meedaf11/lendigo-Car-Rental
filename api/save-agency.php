<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

// Ensure session user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

$agency = $data['agency'];
$car = $data['car'];
$payment = $data['payment'];

try {
    // Step 1: Insert Agency
    $stmt = $pdo->prepare("
        INSERT INTO agency (name, agency_owner_id, description, image, contact_email, phone_number, agency_city, location)
        VALUES (:name, :owner_id, :description, :image, :email, :phone, :city, :location)
    ");

    $stmt->bindParam(':name', $agency['name']);
    $stmt->bindParam(':owner_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':description', $agency['description']);
    $stmt->bindParam(':image', $agency['image']);
    $stmt->bindParam(':email', $agency['email']);
    $stmt->bindParam(':phone', $agency['phone']);
    $stmt->bindParam(':city', $agency['city']);
    $stmt->bindParam(':location', $agency['location']);
    $stmt->execute();

    $agencyId = $pdo->lastInsertId();

    // Step 2: Insert Car
    $stmt = $pdo->prepare("
        INSERT INTO car (agency_id, car_name, car_rating, description, model, places, brand, price_per_day, car_type, image_url, car_fuel, kilometers, isAutomatic)
        VALUES (:agency_id, :car_name, :rating, :description, :model, :places, :brand, :price, :type, :image, :fuel, :km, :gear)
    ");

    $stmt->bindParam(':agency_id', $agencyId, PDO::PARAM_INT);
    $stmt->bindParam(':car_name', $car['name']);
    $zeroRating = 0.0;
    $stmt->bindParam(':rating', $zeroRating);
    $stmt->bindParam(':description', $agency['description']);
    $stmt->bindParam(':model', $car['model']);
    $stmt->bindParam(':places', $car['places'], PDO::PARAM_INT);
    $stmt->bindParam(':brand', $car['brand']);
    $stmt->bindParam(':price', $car['price']);
    $stmt->bindParam(':type', $car['type']);
    $stmt->bindParam(':image', $car['image']);
    $stmt->bindParam(':fuel', $car['fuel']);
    $stmt->bindParam(':km', $car['kilometers']);
    $stmt->bindParam(':gear', $car['gear']);
    $stmt->execute();

    // Step 3: Insert Payment
    $stmt = $pdo->prepare("
        INSERT INTO payments (agency_id, amount, payment_method, payment_status)
        VALUES (:agency_id, :amount, :method, :status)
    ");
    $stmt->bindParam(':agency_id', $agencyId, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $payment['amount']);
    $method = 'card';
    $status = 'completed';
    $stmt->bindParam(':method', $method);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Step 4: Update Solde
    $stmt = $pdo->prepare("SELECT solde FROM agency WHERE agency_id = :agency_id");
    $stmt->bindParam(':agency_id', $agencyId, PDO::PARAM_INT);
    $stmt->execute();
    $currentSolde = $stmt->fetchColumn();
    $currentSolde = $currentSolde ?? 0;

    $newSolde = $currentSolde + $payment['amount'];

    $stmt = $pdo->prepare("UPDATE agency SET solde = :new_solde WHERE agency_id = :agency_id");
    $stmt->bindParam(':new_solde', $newSolde);
    $stmt->bindParam(':agency_id', $agencyId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'agency_id' => $agencyId, 'new_solde' => $newSolde]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
