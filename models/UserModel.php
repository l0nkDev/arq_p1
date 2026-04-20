<?php

require_once("db/Connect.php");

class UserModel
{
    private const BASE_QUERY = "select users.* from users";
    private $db;
    private $users;

    public function __construct()
    {
        $this->db = Connect::connection();
        $this->users = [];
    }

    public function read()
    {
        $query = $this->db->query(self::BASE_QUERY);
        while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {
            $this->users[] = $rows;
        }
        return $this->users;
    }

    public function getLeastBusyId()
    {
        $sql = "SELECT u.registration, COUNT(t.id) as ticket_count
            FROM users u
            LEFT JOIN tickets t ON u.registration = t.admin_id
            WHERE u.role = 'A'
            GROUP BY u.registration
            ORDER BY ticket_count ASC
            LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['registration'] : null;
    }
    public function readId($id)
    {
        $sql = self::BASE_QUERY . " AND users.registration = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
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