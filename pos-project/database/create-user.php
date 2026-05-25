<?php
// Run once to create a test reseller. Delete after use.
require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';

$db = Connection::get();

$name     = 'Teste Reseller';
$username = 'teste';
$password = password_hash('Teste@2025', PASSWORD_DEFAULT);
$email    = 'teste@urafiki.co.mz';
$uid      = 'UID-TESTE-001';

$stmt = $db->prepare(
    "INSERT INTO TblResellers (uid, username, password, name, email, isActive)
     VALUES (?, ?, ?, ?, ?, 5)"
);
$stmt->bind_param('sssss', $uid, $username, $password, $name, $email);

if ($stmt->execute()) {
    $resellerId = $db->insert_id;
    echo "Reseller created. ID: $resellerId\n";
    echo "Username : $username\n";
    echo "Password : Teste@2025\n";
    echo "UID      : $uid\n";

    // Create account with 0 balance
    $stmt2 = $db->prepare("INSERT INTO TblAccounts (resellerId, balance, creditLimit) VALUES (?, 0.00, 0.00)");
    $stmt2->bind_param('i', $resellerId);
    $stmt2->execute();
    $stmt2->close();
    echo "Account  : created (balance 0.00 MT)\n";
} else {
    echo "Error: " . $db->error . "\n";
}

$stmt->close();
