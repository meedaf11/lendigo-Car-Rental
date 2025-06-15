<?php
require_once '../includes/config.php';
header('Content-Type: application/json; charset=UTF-8');

$sql = "SELECT * FROM agency ORDER BY agency_id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Return as JSON
echo json_encode($agencies);

?>