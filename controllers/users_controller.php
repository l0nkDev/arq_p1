<?php

require_once("models/users_model.php");
$users = new users_model();
$data = $users->read();

switch ($action) {
    case "create":
        create($users, $_POST);
        break;
    default:
        read($users, $data);
        break;
}

function create($users, $form) {
    $users->create($form);
    header('Location: /users');
    exit;
}

function read($users, $data) {
    require_once("views/users_view.phtml");
}
?>