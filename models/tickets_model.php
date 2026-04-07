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
            $sql = self::BASE_QUERY . " AND tickets.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($data, $user_id) {
            $sql = "insert into tickets (description, status, student_id, admin_id, location_id) values(:description, :status, :student_id, :admin_id, :location_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':description' => $data['description'],
                ':status' => $data['status'],
                ':student_id' => $user_id,
                ':admin_id' => $data['admin_id'],
                ':location_id' => $data['location_id']
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

        public function delete($id, $user_id) {
            $sql = "DELETE FROM tickets WHERE id = :id AND student_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $user_id]);
        }
    }
    ?>