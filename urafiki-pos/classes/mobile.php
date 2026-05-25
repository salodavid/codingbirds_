<?php
class Mobile {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->pdo = Connection::get();
    }

    public function insert($data) {
        $sql = "INSERT INTO TblMobile
                (msgId, transactionId, uid, msisdn, prefix, amount, debit,
                 rechargeType, pin, serial, balanceAfter, estado, fault, rawResponse, createdAt)
                VALUES
                (:msgId, :transactionId, :uid, :msisdn, :prefix, :amount, :debit,
                 :rechargeType, :pin, :serial, :balanceAfter, :estado, :fault, :rawResponse, NOW())";
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function findByTransactionId($transactionId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblMobile WHERE transactionId = :transactionId LIMIT 1"
        );
        $stmt->execute([':transactionId' => $transactionId]);
        return $stmt->fetch();
    }

    public function checkMsisdnCooldown($msisdn, $seconds) {
        $seconds = (int)$seconds;
        $stmt    = $this->pdo->prepare(
            "SELECT id FROM TblMobile
             WHERE msisdn = :msisdn
             AND createdAt >= NOW() - INTERVAL $seconds SECOND
             AND estado NOT IN (0, 5) LIMIT 1"
        );
        $stmt->execute([':msisdn' => $msisdn]);
        return $stmt->fetch();
    }

    public function getByUid($uid, $dateFrom = null, $dateTo = null) {
        $sql    = "SELECT * FROM TblMobile WHERE uid = :uid";
        $params = [':uid' => $uid];
        if ($dateFrom) { $sql .= " AND DATE(createdAt) >= :dateFrom"; $params[':dateFrom'] = $dateFrom; }
        if ($dateTo)   { $sql .= " AND DATE(createdAt) <= :dateTo";   $params[':dateTo']   = $dateTo;   }
        $sql .= " ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getSummaryByDate($uid, $date) {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) as total, SUM(amount) as totalAmount, SUM(debit) as totalDebit
             FROM TblMobile WHERE uid = :uid AND DATE(createdAt) = :date"
        );
        $stmt->execute([':uid' => $uid, ':date' => $date]);
        return $stmt->fetch();
    }
}
