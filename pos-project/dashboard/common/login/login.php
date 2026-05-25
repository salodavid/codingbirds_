<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../common/connection/pos.urafiki.co.mz.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: ../../index.php?error=empty');
    exit;
}

$db   = Connection::get();
$stmt = $db->prepare("SELECT id, uid, name, email, password FROM TblResellers WHERE username = ? AND isActive = 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$reseller = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$reseller || !password_verify($password, $reseller['password'])) {
    header('Location: ../../index.php?error=invalid');
    exit;
}

$_SESSION['reseller'] = [
    'id'   => $reseller['id'],
    'uid'  => $reseller['uid'],
    'name' => $reseller['name'],
    'email'=> $reseller['email'],
];

header('Location: ../../main.php');
exit;
