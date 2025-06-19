<?php
require_once '../includes/config.php'; // الاتصال بقاعدة البيانات

try {
    // 1. جلب جميع الوكالات
    $sql = "SELECT agency_id FROM agency";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($agencies as $agency) {
        $agency_id = (int)$agency['agency_id'];

        // 2. جلب المتوسط مباشرة من قاعدة البيانات باستخدام AVG
        $sqlAvg = "SELECT AVG(rating) as avg_rating FROM agency_review WHERE agency_id = :agency_id";
        $stmtAvg = $pdo->prepare($sqlAvg);
        $stmtAvg->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
        $stmtAvg->execute();
        $result = $stmtAvg->fetch(PDO::FETCH_ASSOC);

        $average = $result['avg_rating'] !== null ? round($result['avg_rating'], 2) : 3.00;

        // 3. تحديث قيمة التقييم في جدول agency
        $sqlUpdate = "UPDATE agency SET rating = :rating WHERE agency_id = :agency_id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':rating', $average);
        $stmtUpdate->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
        $stmtUpdate->execute();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Agency ratings updated successfully.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
