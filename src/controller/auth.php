<?php
require_once __DIR__.'/../includes/routeHTTP.php';
require_once __DIR__.'/../service/userService.php';

$jsonEncoded = file_get_contents('php://input');
$jsonDecoded = json_decode($jsonEncoded, true);

if (is_array($jsonDecoded)) {
   foreach ($jsonDecoded as $varName => $varValue) {
       $_POST[$varName] = $varValue;
   }
}


$userService = new UserService(new UserDao());
$route = new RouteHTTP();

$route->addRoute('POST', '/auth/login', function($userService) {
    $key = isset($_POST['key']) ? $_POST['key'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $userService->login($key, $password)->response();
});

$route->addRoute('POST', '/auth/signup', function($userService, $pathParams) {
    $userName = isset($_POST['user_name']) ? $_POST['user_name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $userService->signup($userName, $email, $password)->response();
});

$route->addRoute('PUT', '/auth/password', function($userService) {
    $oldPassword = isset($_REQUEST['old_password']) ? $_REQUEST['old_password'] : null;
    $newPassword = isset($_REQUEST['new_password']) ? $_REQUEST['new_password'] : null;
    $userService->changePassword($oldPassword, $newPassword)->response();
});

$route->setService($userService);
$route->enroute();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if($route->isAuthorizedRoute($method, $uri)) {
    $route->execCallback();
} else {
    header('HTTP/1.0 401 Unauthorized');
}
?>
