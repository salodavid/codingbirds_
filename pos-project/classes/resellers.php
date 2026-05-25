<?php
class Resellers {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->db = Connection::get();
    }

    public function authenticate($username, $password, $uid) {
        $stmt = $this->db->prepare(
            "SELECT * FROM TblResellers WHERE username = ? AND uid = ? AND isActive = 5 LIMIT 1"
        );
        $stmt->bind_param('ss', $username, $uid);
        $stmt->execute();
        $reseller = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($reseller && password_verify($password, $reseller['password'])) {
            return $reseller;
        }
        return false;
    }

    public function validateIp($resellerId, $ip) {
        $stmt = $this->db->prepare(
            "SELECT id FROM TblResellerIps WHERE resellerId = ? AND ipAddress = ? AND isActive = 5 LIMIT 1"
        );
        $stmt->bind_param('is', $resellerId, $ip);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getRateLimits($resellerId) {
        $stmt = $this->db->prepare("SELECT * FROM TblRateLimits WHERE resellerId = ? LIMIT 1");
        $stmt->bind_param('i', $resellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getSecurityRules($resellerId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM TblSecurityRules WHERE resellerId = ? OR resellerId IS NULL ORDER BY resellerId DESC LIMIT 1"
        );
        $stmt->bind_param('i', $resellerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
}
