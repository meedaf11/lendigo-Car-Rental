<?php
header("Content-Type: application/json");
require_once '../includes/config.php';

if (!isset($_GET['car_id']) || empty($_GET['car_id'])) {
    echo json_encode(["error" => "Missing car_id"]);
    exit;
}

$car_id = intval($_GET['car_id']);

$sql = "SELECT r.review_id, r.user_id, r.car_id, r.rating, r.review_text, r.created_at, u.username AS user_name
        FROM car_review r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.car_id = :car_id
        ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(":car_id", $car_id, PDO::PARAM_INT);
$stmt->execute();

$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reviews, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
