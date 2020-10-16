<?php
require_once __DIR__.'/../includes/routeHTTP.php';
require_once __DIR__.'/../service/tweetService.php';
require_once __DIR__.'/../includes/utilHTTP.php';

$jsonEncoded = file_get_contents('php://input');
$jsonDecoded = json_decode($jsonEncoded, true);

if (is_array($jsonDecoded)) {
   foreach ($jsonDecoded as $varName => $varValue) {
       $_POST[$varName] = $varValue;
   }
}

$route = new RouteHTTP();
$utilHttp = new utilHTTP(getallheaders());
$tweetService = new TweetService(new TweetDao());
$tweetService->setToken($utilHttp->getJWT());
$route->setService($tweetService);

$route->addRoute('POST', '/tweet', function($tweetService) {
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $tweetService->create($message)->response();
});

$route->addRoute('DELETE', '/tweet/{id:\d+}', function($tweetService, $params) {
    $tweetService->delete($params['id'])->response();
});

$route->addRoute('GET', '/tweet/all', function($tweetService) {
     $tweetService->findAll()->response();
});

$route->addRoute('GET', '/tweet/fav', function($tweetService) {
    $tweetService->findFavorites()->response();
});

$route->addRoute('PUT', '/tweet/like/{id:\d+}', function($tweetService, $params) {
    $tweetService->likeTweet($params['id'])->response();
});

$route->addRoute('DELETE', '/tweet/like/{id:\d+}', function($tweetService, $params) {
    $tweetService->unlikeTweet($params['id'])->response();
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
