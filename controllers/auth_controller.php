<?php

require_once("models/auth_model.php");
$auth = new auth_model();
$error = null;

switch ($action) {
    default:
        login($auth, $_POST);
        break;
    case "logout":
        logout();
        break;
}

function login($auth, $data) {
    global $error;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $auth->authenticate($data);
        if ($user) {
            $_SESSION["user_id"] = $user["registration"];
            $_SESSION["user_role"] = $user["role"];
            header("Location: /");
            exit();
        }
        $error = "Incorrect user or password";
    }
}

function logout() {
    $_SESSION = array();
    session_destroy();
    header("Location: /");
    exit();
}

require_once("views/auth_view.phtml");
?>