<?php
require __DIR__.'/../../vendor/autoload.php';

class RouteHTTP {

    private $routes;
    private $dispatcher;

    private $callback; /* callback asociado a ruta de la API */
    private $pathParams; /* variables incluidas en la url, ejemplo: /user/{id} */
    private $service; /* service invocado desde el callback */

    private const BASE_URL = '/api-mini-twitter';

    public function __construct() {
        $this->routes = array();
    }

    /*
    * agrega un end-point al array de rutas.
    * en este punto no se ha hecho en enrutamiento
    */
    public function addRoute($method, $url, $action) {

        /* valido que los parametros no sean null*/
        if($method == null || $url == null || $action == null)
          return;

        array_push($this->routes, array(
            'method' => $method,
            'url' => SELF::BASE_URL.''.$url,
            'action' => $action
        ));
    }

    /**
    * recorre y enruta todos los end-point agregados
    */
    public function enroute() {

        $this->dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $total_routes = count($this->routes);
            for($i=0; $i<$total_routes; $i++) {
                $r->addRoute($this->routes[$i]['method'], $this->routes[$i]['url'], $this->routes[$i]['action']);
            }
        });
    }

    /**
    * retorna el objeto routerInfo
    */
    private function getRouterInfo($method, $uri) {

        /*
        * esta funcion debe ser invocada despues de enroute, si
        * es llamada antes, agrego validacion para evitar runtime error
        */
        if($this->dispatcher == null)
          return null;

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);
        return $this->dispatcher->dispatch($method, $uri);
    }

    /**
    * verifico que la ruta se encuentre dentro de la lista
    */
    public function isAuthorizedRoute($method, $uri) {
        $routeInfo = $this->getRouterInfo($method, $uri);

        /*
        * esta funcion debe ser invocada despues de enroute, si
        * es llamada antes, agrego validacion para evitar runtime error
        */
        if($routeInfo == null)
          return null;

        if($routeInfo[0] == FastRoute\Dispatcher::NOT_FOUND || $routeInfo[0] == FastRoute\Dispatcher::METHOD_NOT_ALLOWED)
            return false;

        if($routeInfo[0] == FastRoute\Dispatcher::FOUND) {
            /* obtengo el callback asociado a la ruta */
            $this->callback = $routeInfo[1];
            $this->pathParams = $routeInfo[2];
            return true;
        }

        return false;
    }

    public function execCallback() {
        $callback = $this->callback;
        return $callback($this->pathParams, $this->service);
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function setService($service) {
        $this->service = $service;
    }
}
?>
