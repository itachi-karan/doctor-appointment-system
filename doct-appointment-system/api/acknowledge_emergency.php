<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models.php';
global $db;

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}
$id = (int)$data['id'];
$stmt = $db->prepare('UPDATE accidents SET status = ? WHERE id = ?');
$stmt->execute(['notified', $id]);
echo json_encode(['success' => true]);
