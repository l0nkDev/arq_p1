<?php

require_once("db/db.php");

class tickets_model{
    const BASE_QUERY = "select 
	tickets.*, 
	s.name as s_name, 
	s.lastname as s_lastname,
	a.name as a_name, 
	a.lastname as a_lastname 
	from tickets, users s, users a 
	where 
		student_id = s.registration and
		admin_id = a.registration";
    private $db;
    private $tickets;
    private $ticket;

    public function __construct() {
        $this->db = Connect::connection();
        $this->tickets = [];
    }

    public function read() {
        $query = $this->db->query(self::BASE_QUERY . " order by tickets.id asc");
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->tickets[] = $rows;
        }
        return $this->tickets;
    }

    public function read_id($id) {
        $query = $this->db->query(self::BASE_QUERY . " and tickets.id = $id");
        while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
            $this->ticket = $rows[0];
        }
        return $this->ticket;
    }

    public function create($data) {
        $sql = "insert into tickets (description, status, student_id, admin_id) values(:description, :status, :student_id, :admin_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':description' => $data['description'],
            ':status' => $data['status'],
            ':student_id' => $_SESSION['user_id'],
            ':admin_id' => 123456789,
        ]);
    }

    public function update($id, $data) {
        $sql = "update tickets set description = :description, status = :status where id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':description' => $data['description'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $this->db->query("delete from tickets where id = $id");
    }
}
?>