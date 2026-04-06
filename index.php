<?php 
session_start();
require_once("db/db.php");
$fullUri = $_SERVER['REQUEST_URI'] ?? '';
$requestUri = parse_url($fullUri, PHP_URL_PATH);
$uriPath = trim($_SERVER['PATH_INFO'] ?? '', '/');
$parts = explode('/', $uriPath);
$controllerName = !empty($parts[0]) ? $parts[0] : 'auth';
$action = !empty($parts[1]) ? $parts[1] : 'login';
$id = $parts[2] ?? null;

if ($uriPath == '') {
    if (isset($_SESSION['user_id'])) {
        header("Location: /tickets");
    } else {
        header("Location: /auth/login");
    }
    exit;
}

if (!isset($_SESSION['user_id']) && ($controllerName !== 'auth')) {
   header("Location: /auth/login");
   exit;
}

if (isset($_SESSION['user_id']) && ($controllerName == null || $controllerName == '' || ($controllerName == 'auth' && $action == 'login'))) {
   $controllerName = 'tickets';
   header("Location: /tickets");
   exit;
}

require_once("index.phtml");

switch ($controllerName) {
   case 'auth':
      require_once('controllers/auth_controller.php');
      break;
   case 'tickets':
      require_once('controllers/tickets_controller.php');
      break;
   default:
      http_response_code(404);
      echo "404 not found";
      exit;
}
?>