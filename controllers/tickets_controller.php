<?php

require_once("models/tickets_model.php");
$tickets = new tickets_model();
$data = $tickets->read();

switch ($action) {
    case "create":
        create($tickets, $_POST);
        break;
    default:
        read($tickets, $data);
        break;
    case "update":
        if (is_numeric($id)) update($tickets, $_POST, $id);
        break;
    case "delete":
        if (is_numeric($id)) delete($tickets, $id);
        break;
}

function create($tickets, $form) {
    $form["admin_id"] = 123456789;
    $form["location_id"] = 3;
    $tickets->create($form, $_SESSION["user_id"]);
    header('Location: /tickets');
    exit;
}

function read($tickets, $data) {
    require_once("views/tickets_view.phtml");
}

function update($tickets, $form, $id) {
    $tickets->update($id, $form);
    header('Location: /tickets');
    exit;
}

function delete($tickets, $id) {
    $tickets->delete($id, $_SESSION['user_id']);
    header('Location: /tickets');
    exit;
}
?>