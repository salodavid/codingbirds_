<?php
require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';

function getDashboardSummary($uid, $date) {
    $db = Connection::get();

    $stmt = $db->prepare(
        "SELECT COUNT(*) as total, COALESCE(SUM(amount),0) as totalAmount, COALESCE(SUM(debit),0) as totalDebit
         FROM TblMobile WHERE uid = ? AND DATE(createdAt) = ?"
    );
    $stmt->bind_param('ss', $uid, $date);
    $stmt->execute();
    $mobile = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $db->prepare(
        "SELECT COUNT(*) as total, COALESCE(SUM(amount),0) as totalAmount, COALESCE(SUM(debit),0) as totalDebit
         FROM TblTv WHERE uid = ? AND DATE(createdAt) = ?"
    );
    $stmt->bind_param('ss', $uid, $date);
    $stmt->execute();
    $tv = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $db->prepare(
        "SELECT COUNT(*) as total, COALESCE(SUM(amount),0) as totalAmount, COALESCE(SUM(debit),0) as totalDebit
         FROM TblElectricity WHERE uid = ? AND DATE(createdAt) = ?"
    );
    $stmt->bind_param('ss', $uid, $date);
    $stmt->execute();
    $elec = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $db->prepare("SELECT balance FROM TblAccounts a JOIN TblResellers r ON a.resellerId = r.id WHERE r.uid = ? LIMIT 1");
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $account = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return [
        'mobile'  => $mobile,
        'tv'      => $tv,
        'elec'    => $elec,
        'balance' => $account['balance'] ?? 0,
    ];
}

function getRecentMobile($uid, $limit = 10) {
    $db   = Connection::get();
    $stmt = $db->prepare(
        "SELECT msisdn, prefix, amount, debit, rechargeType, estado, createdAt
         FROM TblMobile WHERE uid = ? ORDER BY createdAt DESC LIMIT ?"
    );
    $stmt->bind_param('si', $uid, $limit);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}
