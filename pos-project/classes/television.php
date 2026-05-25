<?php
class Television {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->db = Connection::get();
    }

    public function insert($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO TblTv
             (msgId, transactionId, uid, decoderNumber, package, amount, debit,
              pin, serial, balanceAfter, estado, fault, rawResponse, createdAt)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param(
            'sssssddssdiis',
            $data['msgId'], $data['transactionId'], $data['uid'],
            $data['decoderNumber'], $data['package'], $data['amount'], $data['debit'],
            $data['pin'], $data['serial'], $data['balanceAfter'],
            $data['estado'], $data['fault'], $data['rawResponse']
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function findByTransactionId($transactionId) {
        $stmt = $this->db->prepare("SELECT * FROM TblTv WHERE transactionId = ? LIMIT 1");
        $stmt->bind_param('s', $transactionId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getByUid($uid, $dateFrom = null, $dateTo = null) {
        $sql    = "SELECT * FROM TblTv WHERE uid = ?";
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
}
