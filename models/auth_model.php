<?php

require_once("db/db.php");

class auth_model{
    private $db;
    private $user;

    public function __construct() {
        $this->db = Connect::connection();
        $this->user = [];
    }

    public function getUserByRegistry($registration) {
        $query = $this->db->query(
            "SELECT u.*,
                CASE 
                    WHEN EXISTS (SELECT 1 FROM students s WHERE s.registration = u.registration) THEN 'S'
                    WHEN EXISTS (SELECT 1 FROM admins a WHERE a.registration = u.registration) THEN 'A'
                    WHEN EXISTS (SELECT 1 FROM managers m WHERE m.registration = u.registration) THEN 'M'
                    ELSE 'U'
                END AS role
            FROM users u WHERE u.registration = " . $registration .";");
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->user = $rows;
        }
        return $this->user;
    }
}
?>