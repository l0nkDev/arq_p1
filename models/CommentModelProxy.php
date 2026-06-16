<?php
require_once("models/patterns/CommentModelInterface.php");
require_once("models/CommentModel.php");

class CommentModelProxy implements CommentModelInterface {
    private $realModel;
    private $badWords = ['tonto', 'idiota', 'estupido'];

    public function __construct() {
        $this->realModel = new CommentModel();
    }

    public function getByTicketId($ticketId) {
        return $this->realModel->getByTicketId($ticketId);
    }

    public function create($ticketId, $text) {
        $filteredText = str_ireplace($this->badWords, '***', $text);
        $this->realModel->create($ticketId, $filteredText);
    }

    public function delete($id) {
        $this->realModel->delete($id);
    }
}
?>
