<?php
require_once __DIR__.'/../includes/routeHTTP.php';
require_once __DIR__.'/../service/userService.php';

$userService = new UserService(new UserDao());
$route = new RouteHTTP();

$route->addRoute('POST', '/auth/login', function() {
});

$route->addRoute('POST', '/auth/signup', function($pathParams, $userService) {
    $userName = isset($_POST['user_name']) ? $_POST['user_name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $userService->signup($userName, $email, $password)->response();
});

$route->setService($userService);
$route->enroute();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if($route->isAuthorizedRoute($method, $uri)) {
    $route->execCallback();
} else {
    //error 404
}
?>
