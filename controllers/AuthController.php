<?php
require_once("models/AuthModel.php");

class AuthController
{
    private $auth;
    private $error;

    public function __construct()
    {
        $this->auth = new AuthModel();
    }

    public function handleRequest($action)
    {
        switch ($action) {
            default:
                $this->login($_POST);
                break;
            case "logout":
                $this->logout();
                break;
        }
        exit;
    }
    function login($form)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->auth->authenticate($form);
            if ($user) {
                $_SESSION["user_id"] = $user["registration"];
                $_SESSION["user_role"] = $user["role"];
                header("Location: /");
                exit();
            }
            $this->error = "Incorrect user or password";
        }
        $this->read();
    }

    function logout()
    {
        $_SESSION = array();
        session_destroy();
        header("Location: /");
        exit();
    }

    function read()
    {
        require_once("views/AuthView.php");
        $view = new AuthView();
        $view->render($this->error);
    }
}

?>