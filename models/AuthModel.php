<?php
require_once("db/Connect.php");

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = Connect::connection();
    }

    public function authenticate($form) {
        $sql = "SELECT * FROM users WHERE registration = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$form["registration"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($form["password"], $user['passwordhash'])) {
            return $user; 
        }
        return false;
    }
}
?>