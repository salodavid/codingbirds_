<?php
class Mobile {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function insert($data) {
        $sql = "INSERT INTO TblMobile 
                (msgId, transactionId, uid, msisdn, prefix, amount, debit,
                 rechargeType, pin, serial, balanceAfter, estado, fault, rawResponse, createdAt)
                VALUES
                (:msgId, :transactionId, :uid, :msisdn, :prefix, :amount, :debit,
                 :rechargeType, :pin, :serial, :balanceAfter, :estado, :fault, :rawResponse, NOW())";
        return $this->db->prepare($sql)->execute($data);
    }

    public function findByTransactionId($transactionId) {
        $stmt = $this->db->prepare("SELECT * FROM TblMobile WHERE transactionId = :transactionId LIMIT 1");
        $stmt->execute([':transactionId' => $transactionId]);
        return $stmt->fetch();
    }

    public function checkMsisdnCooldown($msisdn, $seconds = 60) {
        $stmt = $this->db->prepare(
            "SELECT id FROM TblMobile WHERE msisdn = :msisdn 
             AND createdAt >= NOW() - INTERVAL :seconds SECOND 
             AND estado NOT IN (0, 5) LIMIT 1"
        );
        $stmt->execute([':msisdn' => $msisdn, ':seconds' => $seconds]);
        return $stmt->fetch();
    }

    public function getByUid($uid, $dateFrom = null, $dateTo = null) {
        $sql = "SELECT * FROM TblMobile WHERE uid = :uid";
        $params = [':uid' => $uid];
        if ($dateFrom) { $sql .= " AND DATE(createdAt) >= :dateFrom"; $params[':dateFrom'] = $dateFrom; }
        if ($dateTo)   { $sql .= " AND DATE(createdAt) <= :dateTo";   $params[':dateTo']   = $dateTo;   }
        $sql .= " ORDER BY createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
