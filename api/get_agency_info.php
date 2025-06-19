<?php
header("Content-Type: application/json");
require_once '../includes/config.php'; 


if (!isset($_GET['agency_id'])) {
    echo json_encode(["error" => "agency_id is required"]);
    exit;
}

$agency_id = intval($_GET['agency_id']);

try {
    $query = "SELECT * FROM agency WHERE agency_id = :agency_id and status = 'active'" ;

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':agency_id', $agency_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo json_encode(["error" => "Agency not found"]);
    } else {
        $agency = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($agency);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
