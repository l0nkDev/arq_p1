<?php
require_once("db/Connect.php");
require_once("models/patterns/CommentModelInterface.php");

class CommentModel implements CommentModelInterface {
    private PDO $db;
    private array $comments;

    public function __construct() {
        $this->db = Connect::getInstance()->getPDO();
        $this->comments = [];
    }

    public function getByTicketId($ticketId) {
        $sql = "SELECT * FROM comment WHERE ticket_id = :ticket_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ticket_id' => $ticketId]);
        
        $this->comments = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->comments[] = $row;
        }
        return $this->comments;
    }

    public function create($ticketId, $text) {
        $sql = "INSERT INTO comment (ticket_id, text) VALUES (:ticket_id, :text)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':ticket_id' => $ticketId,
            ':text' => $text
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
?>
