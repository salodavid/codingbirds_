<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/resellers.php';
require_once __DIR__ . '/../classes/accounts.php';
require_once __DIR__ . '/../classes/mobile.php';

$input = json_decode(file_get_contents('php://input'), true);

$username      = $input['username']      ?? '';
$password      = $input['password']      ?? '';
$uid           = $input['uid']           ?? '';
$transactionId = $input['transactionId'] ?? '';
$msisdn        = $input['msisdn']        ?? '';
$amount        = $input['amount']        ?? 0;

if (!$username || !$password || !$uid || !$transactionId || !$msisdn || !$amount) {
    echo json_encode(['status' => 'error', 'estado' => 0, 'fault' => 0, 'message' => 'Missing required fields']);
    exit;
}

$resellers = new Resellers();
$reseller  = $resellers->authenticate($username, $password, $uid);

if (!$reseller) {
    echo json_encode(['status' => 'error', 'estado' => 0, 'fault' => 1, 'message' => 'Authentication failed']);
    exit;
}

if (!$resellers->validateIp($reseller['id'], $_SERVER['REMOTE_ADDR'])) {
    echo json_encode(['status' => 'error', 'estado' => 0, 'fault' => 2, 'message' => 'IP not authorized']);
    exit;
}

$mobile   = new Mobile();
$existing = $mobile->findByTransactionId($transactionId);
if ($existing) {
    echo json_encode(['status' => 'duplicate', 'estado' => 5, 'fault' => 5, 'message' => 'Already processed', 'data' => $existing]);
    exit;
}

$rules    = $resellers->getSecurityRules($reseller['id']);
$cooldown = $rules ? $rules['msisdnCooldownSeconds'] : MSISDN_COOLDOWN_SECONDS;

if ($mobile->checkMsisdnCooldown($msisdn, $cooldown)) {
    echo json_encode(['status' => 'rejected', 'estado' => 2, 'fault' => 2, 'message' => 'MSISDN recently recharged, please wait ' . $cooldown . ' seconds']);
    exit;
}

$accounts = new Accounts();
$balance  = $accounts->getBalance($reseller['id']);
if (!$balance || $balance['balance'] < $amount) {
    echo json_encode(['status' => 'error', 'estado' => 0, 'fault' => 3, 'message' => 'Insufficient balance']);
    exit;
}

$prefix = substr($msisdn, 0, 2);

// TODO: Call TicTac API here based on prefix
$msgId        = date('YmdHis') . $uid . rand(1000, 9999);
$estado       = 1;
$fault        = 0;
$rechargeType = 'direct';
$pin          = null;
$serial       = null;
$rawResponse  = [];

$accounts->deduct($reseller['id'], $amount);
$balanceAfter = $accounts->getBalanceAfterDeduct($reseller['id']);

$mobile->insert([
    ':msgId'         => $msgId,
    ':transactionId' => $transactionId,
    ':uid'           => $uid,
    ':msisdn'        => $msisdn,
    ':prefix'        => $prefix,
    ':amount'        => $amount,
    ':debit'         => $amount,
    ':rechargeType'  => $rechargeType,
    ':pin'           => $pin,
    ':serial'        => $serial,
    ':balanceAfter'  => $balanceAfter,
    ':estado'        => $estado,
    ':fault'         => $fault,
    ':rawResponse'   => json_encode($rawResponse)
]);

echo json_encode([
    'status'        => 'success',
    'msgId'         => $msgId,
    'transactionId' => $transactionId,
    'uid'           => $uid,
    'msisdn'        => $msisdn,
    'amount'        => $amount,
    'rechargeType'  => $rechargeType,
    'pin'           => $pin,
    'serial'        => $serial,
    'balanceAfter'  => $balanceAfter,
    'estado'        => $estado,
    'fault'         => $fault
]);
