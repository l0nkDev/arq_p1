<?php 
session_start();
$fullUri = $_SERVER['REQUEST_URI'] ?? '';
$requestUri = parse_url($fullUri, PHP_URL_PATH);
$uriPath = trim($_SERVER['PATH_INFO'] ?? '', '/');
$parts = explode('/', $uriPath);
$controllerName = !empty($parts[0]) ? $parts[0] : 'auth';
$action = !empty($parts[1]) ? $parts[1] : 'login';
$id = $parts[2] ?? null;


if ($controllerName == null || !in_array($controllerName, ['tickets', 'comments'])) {
   header("Location: /tickets");
   exit;
}

require_once("index.phtml");
require_once("db/Connect.php");


$instance1 = Connect::getInstance();
$instance2 = Connect::getInstance();
if ($instance1 === $instance2) {
   error_log("El patrón singleton funciona correctamente!\n");
}


switch ($controllerName) {
   case 'tickets':
      require_once('controllers/TicketController.php');
      $ticket = new TicketController();
      $ticket->handleRequest($action, $id);
      break;
   case 'comments':
      require_once('controllers/CommentController.php');
      $comment = new CommentController();
      $comment->handleRequest($action, $id);
      break;
   default:
      http_response_code(404);
      echo "404 not found";
      exit;
}
?>