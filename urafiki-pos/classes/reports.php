<?php
class Reports {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function log($data) {
        $sql = "INSERT INTO TblReports 
                (uid, accountName, reportDate, filePath, totalTransactions, totalAmount, totalDebit, sentTo, sentAt, createdAt)
                VALUES
                (:uid, :accountName, :reportDate, :filePath, :totalTransactions, :totalAmount, :totalDebit, :sentTo, NOW(), NOW())";
        return $this->db->prepare($sql)->execute($data);
    }

    public function getByUid($uid) {
        $stmt = $this->db->prepare("SELECT * FROM TblReports WHERE uid = :uid ORDER BY reportDate DESC");
        $stmt->execute([':uid' => $uid]);
        return $stmt->fetchAll();
    }
}
