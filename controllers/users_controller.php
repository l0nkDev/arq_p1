<?php
require_once("models/users_model.php");
$users = new users_model();
if ($action == "create") {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $registration = $_POST['registration'];
        $password = $_POST['password'];
        $user = $auth->getUserByRegistry($registration);
        if ($user && password_verify($password, $user['passwordhash'])) {
            $_SESSION['user_id'] = $user['registration'];
            $_SESSION['user_role'] = $user['type'];
            header("Location: /tickets");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
if ($action == "logout") {
    $_SESSION = array();
    session_destroy();
    header("Location: /");
    exit();
}
require_once("views/auth_view.phtml");
?>