<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

require_once __DIR__ . '/../models.php';
global $db;

// Return only pending emergencies
$stmt = $db->prepare("SELECT * FROM accidents WHERE status = 'pending' ORDER BY reported_time ASC");
$stmt->execute();
$new = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([ 'new' => $new ]);
