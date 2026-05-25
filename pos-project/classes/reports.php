<?php
class Reports {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->db = Connection::get();
    }

    public function log($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO TblReports
             (uid, accountName, reportDate, filePath, totalTransactions, totalAmount, totalDebit, sentTo, sentAt, createdAt)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        $stmt->bind_param(
            'ssssiids',
            $data['uid'], $data['accountName'], $data['reportDate'],
            $data['filePath'], $data['totalTransactions'],
            $data['totalAmount'], $data['totalDebit'], $data['sentTo']
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getByUid($uid) {
        $stmt = $this->db->prepare("SELECT * FROM TblReports WHERE uid = ? ORDER BY reportDate DESC");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getByDate($uid, $date) {
        $stmt = $this->db->prepare("SELECT * FROM TblReports WHERE uid = ? AND reportDate = ? LIMIT 1");
        $stmt->bind_param('ss', $uid, $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
}
