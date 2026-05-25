<?php
class Connection {
    private static $db = null;

    public static function get() {
        if (self::$db === null) {
            require_once __DIR__ . '/../../../config/config.php';
            self::$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (self::$db->connect_error) {
                die('Connection failed: ' . self::$db->connect_error);
            }
            self::$db->set_charset('utf8mb4');
        }
        return self::$db;
    }
}
