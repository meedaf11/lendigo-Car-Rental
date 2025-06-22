<?php
require_once 'functions.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = (int) $data['id'];
$answer = $data['answer'] ?? '';
$status = $data['status'];

$success = updateMessage($id, $answer, $status);

echo json_encode(['success' => $success]);
