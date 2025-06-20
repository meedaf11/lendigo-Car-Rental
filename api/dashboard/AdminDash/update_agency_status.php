<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agency_id'], $_POST['new_status'])) {
    $agencyId = intval($_POST['agency_id']);
    $newStatus = $_POST['new_status'];

    if (in_array($newStatus, ['active', 'blocked'])) {
        if (updateAgencyStatus($agencyId, $newStatus)) {
            echo "Agency status updated successfully.";
            header("Location: index.php?page=agencies");

        } else {
            echo "Failed to update status.";

        }
    } else {
        echo "Invalid status.";
    }
} else {
    echo "Invalid request.";
}
?>