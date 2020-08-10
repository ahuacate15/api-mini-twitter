<?php

require_once __DIR__.'/../includes/routeHTTP.php';
require_once __DIR__.'/../includes/connection.php';


$route = new RouteHTTP();
$route->addRoute('GET', '/auth', function() {
    echo "estoy en raiz";
});
$route->addRoute('GET', '/auth/signup', function() {
    $page = $_GET['page'];
    echo "page: ".$page;
});
$route->enroute();

// Fetch method and URI from somewhere
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if($route->isAuthorizedRoute($method, $uri)) {
    $route->execCallback();
} else {
    echo "Ruta incorrecta";
}
