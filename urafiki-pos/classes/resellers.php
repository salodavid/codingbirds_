<?php
class Resellers {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function authenticate($username, $password, $uid) {
        $stmt = $this->db->prepare(
            "SELECT * FROM TblResellers 
             WHERE username = :username AND uid = :uid AND isActive = 1 LIMIT 1"
        );
        $stmt->execute([':username' => $username, ':uid' => $uid]);
        $reseller = $stmt->fetch();
        if ($reseller && password_verify($password, $reseller['password'])) {
            return $reseller;
        }
        return false;
    }

    public function validateIp($resellerId, $ip) {
        $stmt = $this->db->prepare(
            "SELECT id FROM TblResellerIps 
             WHERE resellerId = :resellerId AND ipAddress = :ip AND isActive = 1 LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId, ':ip' => $ip]);
        return $stmt->fetch();
    }

    public function checkRateLimit($resellerId) {
        $stmt = $this->db->prepare("SELECT * FROM TblRateLimits WHERE resellerId = :resellerId LIMIT 1");
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetch();
    }
}
