    <?php

    require_once("db/Connect.php");

    class TicketModel{
        private const BASE_QUERY = "select 
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

        public function readId($id) {
            $sql = self::BASE_QUERY . " AND tickets.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($form, $user_id) {
            $sql = "insert into tickets (description, status, student_id, admin_id, location_id, title, imageurl) values(:description, :status, :student_id, :admin_id, :location_id, :title, :imageurl)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':description' => $form['description'],
                ':status' => $form['status'],
                ':student_id' => $user_id,
                ':admin_id' => $form['admin_id'],
                ':location_id' => $form['location_id'],
                ':title' => $form['title'],
                ':imageurl' => $form['imageurl'],
            ]);
        }

        public function updateValidated($form, $id, $user_id) {
            $sql = "update tickets set status = :status where id = :id and admin_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':status' => $form['status'],
                ':id' => $id,
                ':user_id' => $user_id
            ]);
        }

        public function closeValidated($id, $user_id) {
            $sql = "update tickets set status = 'CLOSED' where id = :id and status = 'RETURNED' and student_id = :student_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':student_id' => $user_id,
            ]);
        }

        public function deleteValidated($id, $user_id) {
            $sql = "DELETE FROM tickets WHERE id = :id AND student_id = :user_id AND status != 'RETURNED'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $user_id]);
        }
    }
    ?>