<?php
require_once("models/TicketModel.php");
require_once("models/TicketAuditObserver.php");

class TicketController {
    private TicketModel $tickets;

    public function __construct() {
        $this->tickets = new TicketModel();
        $this->tickets->attach(new TicketAuditObserver($this->tickets));
    }

    public function handleRequest($action, $id = null) {
        switch ($action) {
            case "create":
                $this->create($_POST);
                break;
            default:
                $this->read();
                break;
            case "update":
                if (is_numeric($id)) $this->update($_POST, $id);
                break;
            case "delete":
                if (is_numeric($id)) $this->delete($id);
                break;
        }
    }

    function create($form) {
        $form['imageurl'] = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/tickets/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_', true) . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $form['imageurl'] = $targetPath;
            }
        }
        $this->tickets->create($form);
        header('Location: /tickets');
        exit;
    }

    function read() {
        require_once("views/TicketView.php");
        $tickets = $this->tickets->read();
        $view = new TicketView();
        $view->render($tickets);
        exit;
    }

    function update($form, $id) {
        $this->tickets->update($form, $id);
        header('Location: /tickets');
        exit;
    }

    function delete($id) {
        $this->tickets->delete($id);
        header('Location: /tickets');
        exit;
    }
}
?>