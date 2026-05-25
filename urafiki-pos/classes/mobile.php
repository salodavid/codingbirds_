<?php
class Mobile {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->db = Connection::get();
    }

    public function insert($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO TblMobile
             (msgId, transactionId, uid, msisdn, prefix, amount, debit,
              rechargeType, pin, serial, balanceAfter, estado, fault, rawResponse, createdAt)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param(
            'sssssddsssdii s',
            $data['msgId'], $data['transactionId'], $data['uid'],
            $data['msisdn'], $data['prefix'], $data['amount'], $data['debit'],
            $data['rechargeType'], $data['pin'], $data['serial'],
            $data['balanceAfter'], $data['estado'], $data['fault'], $data['rawResponse']
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function findByTransactionId($transactionId) {
        $stmt = $this->db->prepare("SELECT * FROM TblMobile WHERE transactionId = ? LIMIT 1");
        $stmt->bind_param('s', $transactionId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function checkMsisdnCooldown($msisdn, $seconds) {
        $seconds = (int)$seconds;
        $stmt    = $this->db->prepare(
            "SELECT id FROM TblMobile
             WHERE msisdn = ?
             AND createdAt >= NOW() - INTERVAL $seconds SECOND
             AND estado NOT IN (0, 5) LIMIT 1"
        );
        $stmt->bind_param('s', $msisdn);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getByUid($uid, $dateFrom = null, $dateTo = null) {
        $sql    = "SELECT * FROM TblMobile WHERE uid = ?";
        $types  = 's';
        $params = [$uid];
        if ($dateFrom) { $sql .= " AND DATE(createdAt) >= ?"; $types .= 's'; $params[] = $dateFrom; }
        if ($dateTo)   { $sql .= " AND DATE(createdAt) <= ?"; $types .= 's'; $params[] = $dateTo;   }
        $sql .= " ORDER BY createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getSummaryByDate($uid, $date) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total, SUM(amount) as totalAmount, SUM(debit) as totalDebit
             FROM TblMobile WHERE uid = ? AND DATE(createdAt) = ?"
        );
        $stmt->bind_param('ss', $uid, $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
}
