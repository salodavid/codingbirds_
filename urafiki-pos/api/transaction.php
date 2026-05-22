<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/db.php';
require_once __DIR__ . '/../classes/resellers.php';
require_once __DIR__ . '/../classes/mobile.php';

$input         = json_decode(file_get_contents('php://input'), true);
$username      = $input['username']      ?? '';
$password      = $input['password']      ?? '';
$uid           = $input['uid']           ?? '';
$transactionId = $input['transactionId'] ?? '';

$resellers = new Resellers();
$reseller  = $resellers->authenticate($username, $password, $uid);

if (!$reseller) {
    echo json_encode(['status' => 'error', 'message' => 'Authentication failed']);
    exit;
}

$mobile      = new Mobile();
$transaction = $mobile->findByTransactionId($transactionId);

if (!$transaction) {
    echo json_encode(['status' => 'error', 'message' => 'Transaction not found']);
    exit;
}

echo json_encode(['status' => 'success', 'data' => $transaction]);
