<?php
require_once 'config.php'; // تأكد أنه يحتوي على session_start و$pdo

function getAgencyBookingsCount($agency_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE car_id IN (SELECT car_id FROM car WHERE agency_id = ?)");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyCarsCount($agency_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM car WHERE agency_id = ?");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn();
}

function getAgencyTotalEarnings($agency_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE agency_id = ?");
    $stmt->execute([$agency_id]);
    return $stmt->fetchColumn() ?: 0;
}

function getAgencyAverageRating($agency_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(rating) FROM agency_review WHERE agency_id = ?");
    $stmt->execute([$agency_id]);
    return round($stmt->fetchColumn(), 2) ?: 0;
}
