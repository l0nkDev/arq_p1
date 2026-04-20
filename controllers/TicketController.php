<?php
require_once("models/TicketModel.php");
require_once("models/UserModel.php");
require_once("models/LocationModel.php");

class TicketController {
    private $tickets;
    private $users;
    private $locations;

    public function __construct() {
        $this->tickets = new TicketModel();
        $this->users = new UserModel();
        $this->locations = new LocationModel();
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
            case "close":
                if (is_numeric($id)) $this->close($id);
                break;
            case "delete":
                if (is_numeric($id)) $this->delete($id);
                break;
        }
    }

    function create($form) {
        if ($_SESSION["user_role"] !== "S") {
            header('Location: /');
            exit;
        };
        $form["admin_id"] = $this->users->getLeastBusyId();
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/tickets/';
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_', true) . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $form['imageurl'] = $targetPath;
            }
        }
        $this->tickets->create($form, $_SESSION["user_id"]);
        header('Location: /tickets');
        exit;
    }

    function read() {
        require_once("views/TicketView.php");
        $tickets = $this->tickets->read();
        $locations = $this->locations->read();
        $view = new TicketView();
        $view->render($tickets, $locations);
        exit;
    }

    function update($form, $id) {
        if ($_SESSION["user_role"] !== "A") {
            header('Location: /');
            exit;
        }
        $this->tickets->updateValidated($form, $id, $_SESSION["user_id"]);
        header('Location: /tickets');
        exit;
    }

    function close($id) {
        if ($_SESSION["user_role"] !== "S") {
            header('Location: /');
            exit;
        };
        $this->tickets->closeValidated($id, $_SESSION['user_id']);
        header('Location: /tickets');
        exit;
    }

    function delete($id) {
        if ($_SESSION["user_role"] !== "S") {
            header('Location: /');
            exit;
        };
        $this->tickets->deleteValidated($id, $_SESSION['user_id']);
        header('Location: /tickets');
        exit;
    }
}
?>