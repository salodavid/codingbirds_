<?php
class Television {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->pdo = Connection::get();
    }

    public function insert($data) {
        $sql = "INSERT INTO TblTv
                (msgId, transactionId, uid, decoderNumber, package, amount, debit,
                 pin, serial, balanceAfter, estado, fault, rawResponse, createdAt)
                VALUES
                (:msgId, :transactionId, :uid, :decoderNumber, :package, :amount, :debit,
                 :pin, :serial, :balanceAfter, :estado, :fault, :rawResponse, NOW())";
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function findByTransactionId($transactionId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblTv WHERE transactionId = :transactionId LIMIT 1"
        );
        $stmt->execute([':transactionId' => $transactionId]);
        return $stmt->fetch();
    }

    public function getByUid($uid, $dateFrom = null, $dateTo = null) {
        $sql    = "SELECT * FROM TblTv WHERE uid = :uid";
        $params = [':uid' => $uid];
        if ($dateFrom) { $sql .= " AND DATE(createdAt) >= :dateFrom"; $params[':dateFrom'] = $dateFrom; }
        if ($dateTo)   { $sql .= " AND DATE(createdAt) <= :dateTo";   $params[':dateTo']   = $dateTo;   }
        $sql .= " ORDER BY createdAt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
