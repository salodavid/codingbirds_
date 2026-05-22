<?php
class Accounts {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->pdo = $pdo;
    }

    public function getBalance($resellerId) {
        $stmt = $this->pdo->prepare(
            "SELECT balance FROM TblAccounts WHERE resellerId = :resellerId LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetch();
    }

    public function deduct($resellerId, $amount) {
        $stmt = $this->pdo->prepare(
            "UPDATE TblAccounts SET balance = balance - :amount
             WHERE resellerId = :resellerId AND balance >= :amount"
        );
        $stmt->execute([':resellerId' => $resellerId, ':amount' => $amount]);
        return $stmt->rowCount() > 0;
    }

    public function getBalanceAfterDeduct($resellerId) {
        $stmt = $this->pdo->prepare(
            "SELECT balance FROM TblAccounts WHERE resellerId = :resellerId LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId]);
        $row = $stmt->fetch();
        return $row ? $row['balance'] : 0;
    }

    public function getDepositHistory($resellerId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblBankDeposits WHERE resellerId = :resellerId ORDER BY createdAt DESC"
        );
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetchAll();
    }
}
