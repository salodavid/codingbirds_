<?php
class Resellers {
    private $pdo;

    public function __construct() {
        require_once __DIR__ . '/../common/connection/pos.urafiki.co.mz.php';
        $this->pdo = $pdo;
    }

    public function authenticate($username, $password, $uid) {
        $stmt = $this->pdo->prepare(
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
        $stmt = $this->pdo->prepare(
            "SELECT id FROM TblResellerIps
             WHERE resellerId = :resellerId AND ipAddress = :ip AND isActive = 1 LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId, ':ip' => $ip]);
        return $stmt->fetch();
    }

    public function getRateLimits($resellerId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblRateLimits WHERE resellerId = :resellerId LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetch();
    }

    public function getSecurityRules($resellerId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM TblSecurityRules
             WHERE resellerId = :resellerId OR resellerId IS NULL
             ORDER BY resellerId DESC LIMIT 1"
        );
        $stmt->execute([':resellerId' => $resellerId]);
        return $stmt->fetch();
    }
}
