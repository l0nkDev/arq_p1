<?php

require_once("db/db.php");

class users_model{
    const BASE_QUERY = "select users.* from users";
    private $db;
    private $users;
    private $user;

    public function __construct() {
        $this->db = Connect::connection();
        $this->users = [];
    }

    public function read() {
        if ($_SESSION["user_role"] !== "M") return;
        $query = $this->db->query(self::BASE_QUERY);
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->users[] = $rows;
        }
        return $this->users;
    }

    public function read_id($id) {
        $query = $this->db->query(self::BASE_QUERY . " and users.registration = $id");
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->user = $rows[0];
        }
        return $this->user;
    }

    public function create($data) {
        if ($_SESSION["user_role"] !== "M") return;
        $passwordhash = password_hash($data["password"], PASSWORD_DEFAULT);
        $sql = "insert into users (registration, passwordhash, name, lastname, phone, role) values(:registration, :passwordhash, :name, :lastname, :phone, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':registration' => $data['registration'],
            ':passwordhash' => $passwordhash,
            ':name' => $data['name'],
            ':lastname' => $data['lastname'],
            ':phone' => $data['phone'],
            ':role' => $data['role']
        ]);
    }
}
?>