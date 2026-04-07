<?php

require_once("db/db.php");

class auth_model {
    private $db;

    public function __construct() {
        $this->db = Connect::connection();
    }

    public function authenticate($data) {
        $sql = "SELECT * FROM users WHERE registration = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data["registration"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($data["password"], $user['passwordhash'])) {
            return $user; 
        }
        return false;
    }
}
?>