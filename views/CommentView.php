<?php
class CommentView {
    public $ticket;
    public $comments;

    public function render($ticket, $comments) {
        $this->ticket = $ticket;
        $this->comments = $comments;
        include_once("views/CommentView.phtml");
    }
}
?>
