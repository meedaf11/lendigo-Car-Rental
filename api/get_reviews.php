<?php

require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

try {

    $carStmt = $pdo->prepare("
        SELECT u.username AS username, r.rating, r.review_text, r.car_id
        FROM car_review r
        JOIN users u ON r.user_id = u.user_id
        ORDER BY r.rating DESC
    ");
    $carStmt->execute();
    $carReviews = [];
    while ($row = $carStmt->fetch(PDO::FETCH_ASSOC)) {
        $carReviews[] = [
            "username" => $row['username'],
            "rating" => $row['rating'],
            "text" => $row['review_text'],
            "car_id" => $row['car_id'],
            "button" => "See Car"
        ];
    }

    
    $agencyStmt = $pdo->prepare("
        SELECT u.username AS username, r.rating, r.review_text, r.agency_id
        FROM agency_review r
        JOIN users u ON r.user_id = u.user_id
        ORDER BY r.rating DESC
    ");
    $agencyStmt->execute();
    $agencyReviews = [];
    while ($row = $agencyStmt->fetch(PDO::FETCH_ASSOC)) {
        $agencyReviews[] = [
            "username" => $row['username'],
            "rating" => $row['rating'],
            "text" => $row['review_text'],
            "agency_id" => $row['agency_id'],
            "button" => "See Agency"
        ];
    }

    $allReviews = [
        "carReviews" => $carReviews,
        "agencyReviews" => $agencyReviews
    ];

    file_put_contents(__DIR__ . '/reviews.json', json_encode($allReviews, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    
    // Optionally also output to browser
    echo json_encode($allReviews, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        "error" => "حدث خطأ في جلب البيانات: " . $e->getMessage()
    ]);
}

?>