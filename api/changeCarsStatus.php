<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

try {
    // 1. جلب كل الوكالات
    $sql = "SELECT agency_id, status, solde FROM agency";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $activatedCars = 0;
    $blockedCars = 0;
    $blockedBySolde = 0;

    foreach ($agencies as $agency) {
        $agency_id = $agency['agency_id'];
        $status = $agency['status'];

        if ($status === 'blocked') {
            // 🚫 حظر كل السيارات التابعة
            $sqlBlock = "UPDATE car SET status = 'blocked' WHERE agency_id = :agency_id";
            $stmtBlock = $pdo->prepare($sqlBlock);
            $stmtBlock->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
            $stmtBlock->execute();
            $blockedCars += $stmtBlock->rowCount();
        } elseif ($status === 'active') {
            // ✅ تفعيل مؤقت لكل السيارات
            $sqlActivate = "UPDATE car SET status = 'active' WHERE agency_id = :agency_id";
            $stmtActivate = $pdo->prepare($sqlActivate);
            $stmtActivate->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
            $stmtActivate->execute();
            $activatedCars += $stmtActivate->rowCount();

            // ❌ إعادة حظر السيارات التي لا يغطي رصيدها 10% من السعر اليومي
            $sqlCheckSolde = "
                UPDATE car
                JOIN agency ON car.agency_id = agency.agency_id
                SET car.status = 'blocked'
                WHERE car.agency_id = :agency_id
                AND (car.price_per_day * 0.10) > agency.solde
            ";
            $stmtCheckSolde = $pdo->prepare($sqlCheckSolde);
            $stmtCheckSolde->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
            $stmtCheckSolde->execute();
            $blockedBySolde += $stmtCheckSolde->rowCount();
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'All agencies processed successfully.',
        'cars_activated' => $activatedCars,
        'cars_blocked_due_to_solde' => $blockedBySolde,
        'cars_blocked_by_agency_status' => $blockedCars
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>