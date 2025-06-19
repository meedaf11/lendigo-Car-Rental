<?php
require_once '../includes/config.php'; // الاتصال بقاعدة البيانات

try {
    // 1. جلب جميع السيارات
    $sql = "SELECT car_id FROM car";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cars as $car) {
        $car_id = (int)$car['car_id'];

        // 2. حساب المتوسط من جدول car_review
        $sqlAvg = "SELECT AVG(rating) AS avg_rating FROM car_review WHERE car_id = :car_id";
        $stmtAvg = $pdo->prepare($sqlAvg);
        $stmtAvg->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmtAvg->execute();
        $result = $stmtAvg->fetch(PDO::FETCH_ASSOC);

        $average = $result['avg_rating'] !== null ? round($result['avg_rating'], 2) : 3.00;

        // 3. تحديث car.car_rating
        $sqlUpdate = "UPDATE car SET car_rating = :rating WHERE car_id = :car_id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':rating', $average);
        $stmtUpdate->bindParam(':car_id', $car_id, PDO::PARAM_INT);
        $stmtUpdate->execute();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Car ratings updated successfully.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
