<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

session_unset();
session_destroy();

echo json_encode(["status" => "success", "message" => "Logged out successfully"]);
?>
