<?php
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$username = 'teste';
$password = 'password';

$db = Connection::get();
$stmt = $db->prepare("SELECT id, username, password, isActive FROM TblResellers WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo '<pre>';
if (!$row) {
    echo 'USER NOT FOUND in DB';
} else {
    echo 'User found: ' . $row['username'] . PHP_EOL;
    echo 'isActive: ' . $row['isActive'] . PHP_EOL;
    echo 'Hash in DB: ' . $row['password'] . PHP_EOL;
    echo 'password_verify result: ' . (password_verify($password, $row['password']) ? 'TRUE' : 'FALSE') . PHP_EOL;
    $newHash = password_hash($password, PASSWORD_BCRYPT);
    echo 'Fresh hash of "password": ' . $newHash . PHP_EOL;
}
echo '</pre>';
