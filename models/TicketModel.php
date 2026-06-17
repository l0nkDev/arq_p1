    <?php

    require_once("db/Connect.php");
    require_once("models/patterns/Subject.php");

    class TicketModel extends Subject {
        private const string BASE_QUERY = "select * from ticket";
        private $db;
        private $tickets;
        private string $lastAction = '';
        private array $lastData = [];

        public function __construct() {
            $this->db = Connect::getInstance()->getPDO();
            $this->tickets = [];
        }

        public function getLastAction(): string {
            return $this->lastAction;
        }

        public function getLastData(): array {
            return $this->lastData;
        }

        public function read() {
            $query = $this->db->query(self::BASE_QUERY . " order by id asc");
            while($rows= $query->fetch(PDO::FETCH_ASSOC)) {
                $this->tickets[] = $rows;
            }
            return $this->tickets;
        }

        public function readId($id) {
            $sql = self::BASE_QUERY . " WHERE ticket.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($form) {
            $sql = "insert into ticket (description, status, title, imageurl) values(:description, :status, :title, :imageurl)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':description' => $form['description'],
                ':status' => $form['status'],
                ':title' => $form['title'],
                ':imageurl' => $form['imageurl'],
            ]);
            $this->lastAction = "CREATE";
            $this->lastData = $form;
            $this->notify();
        }

        public function update($form, $id) {
            $sql = "update ticket set status = :status where id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':status' => $form['status'],
                ':id' => $id
            ]);
            $form['id'] = $id;
            $this->lastAction = "UPDATE";
            $this->lastData = $form;
            $this->notify();
        }

        public function delete($id) {
            $sql = "DELETE FROM ticket WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $this->lastAction = "DELETE";
            $this->lastData = ['id' => $id];
            $this->notify();
        }
    }
    ?>