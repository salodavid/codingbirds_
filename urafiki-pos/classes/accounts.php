<?php
class Accounts {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->db = Connection::get();
    }

    public function getBalance($resellerId) {
        $stmt = $this->db->prepare("SELECT balance FROM TblAccounts WHERE resellerId = ? LIMIT 1");
        $stmt->bind_param('i', $resellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function deduct($resellerId, $amount) {
        $stmt = $this->db->prepare(
            "UPDATE TblAccounts SET balance = balance - ? WHERE resellerId = ? AND balance >= ?"
        );
        $stmt->bind_param('did', $amount, $resellerId, $amount);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }

    public function getBalanceAfterDeduct($resellerId) {
        $stmt = $this->db->prepare("SELECT balance FROM TblAccounts WHERE resellerId = ? LIMIT 1");
        $stmt->bind_param('i', $resellerId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? $row['balance'] : 0;
    }

    public function getDepositHistory($resellerId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM TblBankDeposits WHERE resellerId = ? ORDER BY createdAt DESC"
        );
        $stmt->bind_param('i', $resellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
}
