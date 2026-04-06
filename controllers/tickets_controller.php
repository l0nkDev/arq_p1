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
        update($tickets, $_POST, $id);
        break;
    case "delete":
        if (is_numeric($id)) delete($tickets, $data, $id);
        break;
}

function create($tickets, $form) {
    $tickets->create($form);
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

function delete($tickets, $data, $id) {
    $foundrow = array_find($data, function($row) use ($id) {
        return $row["id"] == $id;
    });
    if ($foundrow["student_id"] == $_SESSION["user_id"]) {
        $tickets->delete($id);
    }
    header('Location: /tickets');
    exit;
}
?>