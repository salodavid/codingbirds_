<?php
class Accounts {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getBalance($resellerId) {
        $stmt = $this->db->prepare("SELECT balance FROM TblAccounts WHERE resellerId = :resellerId LIMIT 1");
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetch();
    }

    public function deduct($resellerId, $amount) {
        $stmt = $this->db->prepare(
            "UPDATE TblAccounts SET balance = balance - :amount 
             WHERE resellerId = :resellerId AND balance >= :amount"
        );
        $stmt->execute([':resellerId' => $resellerId, ':amount' => $amount]);
        return $stmt->rowCount() > 0;
    }

    public function getBalanceAfterDeduct($resellerId) {
        $stmt = $this->db->prepare("SELECT balance FROM TblAccounts WHERE resellerId = :resellerId LIMIT 1");
        $stmt->execute([':resellerId' => $resellerId]);
        $row = $stmt->fetch();
        return $row ? $row['balance'] : 0;
    }
}
