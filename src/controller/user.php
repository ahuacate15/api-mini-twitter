<?php
require_once __DIR__.'/../includes/routeHTTP.php';
require_once __DIR__.'/../service/userService.php';
require_once __DIR__.'/../includes/utilHTTP.php';

$jsonEncoded = file_get_contents('php://input');
$jsonDecoded = json_decode($jsonEncoded, true);

if(is_array($jsonDecoded)) {
    foreach ($jsonDecoded as $var => $value) {
        $_POST[$var] = $value;
    }
}

$route = new RouteHTTP();
$utilHttp = new utilHTTP(getallheaders());
$userService = new UserService(new UserDao());
$userService->setToken($utilHttp->getJWT());
$route->setService($userService);

$route->addRoute('GET', '/user/profile', function($userService) {
    $userService->findProfileByUserNameToken()->response();
});

$route->addRoute('PUT', '/user/profile', function($userService) {
    $field = $_REQUEST['field'];
    $value = $_REQUEST['value'];
    $userService->updateUserField($field, $value)->response();
});

$route->addRoute('POST', '/user/photo', function($userService) {
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;
    $userService->uploadPhoto($image)->response();
});

$route->enroute();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if($route->isAuthorizedRoute($method, $uri)) {
    $route->execCallback();
} else {
    header('HTTP/1.0 401 Unauthorized');
}

?>
