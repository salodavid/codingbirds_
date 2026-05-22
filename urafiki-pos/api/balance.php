<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/resellers.php';
require_once __DIR__ . '/../classes/accounts.php';

$input    = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$uid      = $input['uid']      ?? '';

$resellers = new Resellers();
$reseller  = $resellers->authenticate($username, $password, $uid);

if (!$reseller) {
    echo json_encode(['status' => 'error', 'message' => 'Authentication failed']);
    exit;
}

if (!$resellers->validateIp($reseller['id'], $_SERVER['REMOTE_ADDR'])) {
    echo json_encode(['status' => 'error', 'message' => 'IP not authorized']);
    exit;
}

$accounts = new Accounts();
$balance  = $accounts->getBalance($reseller['id']);

echo json_encode([
    'status'  => 'success',
    'uid'     => $uid,
    'balance' => $balance ? $balance['balance'] : 0
]);
