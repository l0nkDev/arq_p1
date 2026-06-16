<?php
interface CommentModelInterface {
    public function getByTicketId($ticketId);
    public function create($ticketId, $text);
    public function delete($id);
}
?>
