<?php
require_once("models/CommentModelProxy.php");
require_once("models/TicketModel.php");
require_once("models/patterns/CommentModelInterface.php");

class CommentController {
    private CommentModelInterface $comments;
    private TicketModel $tickets;

    public function __construct() {
        $this->comments = new CommentModelProxy();
        $this->tickets = new TicketModel();
    }

    public function handleRequest($action, $id = null) {
        switch ($action) {
            case "read":
                if (is_numeric($id)) $this->read($id);
                break;
            case "create":
                $this->create($_POST);
                break;
            case "delete":
                if (is_numeric($id)) $this->delete($id, $_GET['ticket_id'] ?? null);
                break;
            default:
                header('Location: /tickets');
                break;
        }
    }

    function read($ticketId) {
        $ticket = $this->tickets->readId($ticketId);

        if (!$ticket) {
            header('Location: /tickets');
            exit;
        }

        require_once("views/CommentView.php");
        $commentList = $this->comments->getByTicketId($ticketId);
        $view = new CommentView();
        $view->render($ticket, $commentList);
        exit;
    }

    function create($form) {
        if (isset($form['ticket_id']) && isset($form['text'])) {
            $this->comments->create($form['ticket_id'], $form['text']);
        }
        header('Location: /comments/read/' . $form['ticket_id']);
        exit;
    }

    function delete($id, $ticketId) {
        $this->comments->delete($id);
        $redirectUrl = $ticketId ? '/comments/read/' . $ticketId : '/tickets';
        header('Location: ' . $redirectUrl);
        exit;
    }
}
?>
