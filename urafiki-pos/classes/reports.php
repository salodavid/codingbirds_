<?php
class Reports {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->pdo = $pdo;
    }

    public function log($data) {
        $sql = "INSERT INTO TblReports
                (uid, accountName, reportDate, filePath, totalTransactions, totalAmount, totalDebit, sentTo, sentAt, createdAt)
                VALUES
                (:uid, :accountName, :reportDate, :filePath, :totalTransactions, :totalAmount, :totalDebit, :sentTo, NOW(), NOW())";
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function getByUid($uid) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblReports WHERE uid = :uid ORDER BY reportDate DESC"
        );
        $stmt->execute([':uid' => $uid]);
        return $stmt->fetchAll();
    }

    public function getByDate($uid, $date) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblReports WHERE uid = :uid AND reportDate = :date LIMIT 1"
        );
        $stmt->execute([':uid' => $uid, ':date' => $date]);
        return $stmt->fetch();
    }
}
