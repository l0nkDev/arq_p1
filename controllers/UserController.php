<?php
require_once("models/UserModel.php");
class UserController {
    private $users;

    public function __construct() {
        $this->users = new UserModel();
    }

    function handleRequest($action) {
        switch ($action) {
            case "create":
                $this->create($_POST);
                break;
            default:
                $this->read();
                break;
        }
    }

    function create($form) {
        if ($_SESSION["user_role"] !== "M") {
            header('Location: /');
            exit;
        };
        $this->users->create($form);
        header('Location: /users');
        exit;
    }

    function read() {
        if ($_SESSION["user_role"] !== "M") {
            header('Location: /');
            exit;
        };
        require_once("views/UserView.php");
        $data = $this->users->read();
        $view = new UserView();
        $view->render($data);
    }
}
?>