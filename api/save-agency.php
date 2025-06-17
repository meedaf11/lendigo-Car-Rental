<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

// Step 1: Insert into agency
$agency = $data['agency'];
$car = $data['car'];
$payment = $data['payment'];

try {
    // Insert agency
    $stmt = $pdo->prepare("INSERT INTO agency (name, agency_owner_id, description, image, contact_email, phone_number, agency_city, location) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $agency['name'],
        1, // Replace with actual logged-in user ID if applicable
        $agency['description'],
        $agency['image'],
        $agency['email'],
        $agency['phone'],
        $agency['city'],
        $agency['location']
    ]);

    $agencyId = $pdo->lastInsertId();

    // Insert car
    $stmt = $pdo->prepare("INSERT INTO car (agency_id, car_name, car_rating, description, model, places, brand, price_per_day, car_type, image_url, car_fuel, kilometers, isAutomatic) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $agencyId,
        $car['name'],
        0.0,
        $agency['description'], // optional reuse
        $car['model'],
        $car['places'],
        $car['brand'],
        $car['price'],
        $car['type'],
        $car['image'],
        $car['fuel'],
        $car['kilometers'],
        $car['gear']
    ]);

    // Insert payment
    $stmt = $pdo->prepare("INSERT INTO payments (agency_id, amount, payment_method, payment_status) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $agencyId,
        $payment['amount'],
        'card',
        'completed'
    ]);

    // Step 4: Update solde in agency
    $stmt = $pdo->prepare("SELECT solde FROM agency WHERE agency_id = ?");
    $stmt->execute([$agencyId]);
    $currentSolde = $stmt->fetchColumn();
    $currentSolde = $currentSolde ?? 0; // in case NULL

    $newSolde = $currentSolde + $payment['amount'];

    $stmt = $pdo->prepare("UPDATE agency SET solde = ? WHERE agency_id = ?");
    $stmt->execute([$newSolde, $agencyId]);

    echo json_encode(['success' => true, 'agency_id' => $agencyId, 'new_solde' => $newSolde]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
