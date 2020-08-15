<?php
use PHPUnit\Framework\TestCase;

class routeHTTPTest extends TestCase {

    public function testInstance() {
        $instance = new RouteHTTP();
        $this->assertInstanceOf(RouteHTTP::class, $instance);
        return $instance;
    }

    /**
    * @depends testInstance
    */
    public function testAddRoute($instance) {
        $this->assertEquals(array(), $instance->getRoutes());

        $instance->addRoute('GET', '/auth', function(){ return true; });
        $instance->addRoute('POST', '/auth', function() { return 100; });
        $instance->addRoute(null, '/auth', function() { return 100; });
        $instance->addRoute('POST', null, function() { return 100; });
        $instance->addRoute('POST', '/auth', null);

        $this->assertEquals(2, count($instance->getRoutes()));
        $this->assertEquals('/api-mini-twitter/auth', $instance->getRoutes()[0]['url']);
    }

    /**
    * @depends testInstance
    */
    public function testIsAuthorizedRouteWhenNULL($instance) {
        $this->assertNull($instance->isAuthorizedRoute('GET', '/api-mini-twitter/auth'));
    }

    /**
    * @depends testInstance
    */
    public function testIsAuthorizedRoute($instance) {
        $instance->enroute();
        $this->assertTrue($instance->isAuthorizedRoute('GET', '/api-mini-twitter/auth'));
        $this->assertTrue($instance->isAuthorizedRoute('POST', '/api-mini-twitter/auth'));
        $this->assertFalse($instance->isAuthorizedRoute('PUT', '/api-mini-twitter/auth'));
    }

    /**
    * @depends testInstance
    */
    public function testExecCallback($instance) {
        $instance->isAuthorizedRoute('GET', '/api-mini-twitter/auth');
        $this->assertTrue($instance->execCallback());

        $instance->isAuthorizedRoute('POST', '/api-mini-twitter/auth');
        $this->assertEquals(100, $instance->execCallback());
    }

    /**
    * @depends testInstance
    */
    public function testExecCallbackWithService($instance) {

        $instance->addRoute('GET', '/auth/{id:\d+}', function($params, $service) {
            $id = $params['id'];
            return $service($id, 2);
        });

        $instance->setService(function($a, $b) {
          return $a + $b;
        });

        $instance->enroute();
        $instance->isAuthorizedRoute('GET', '/api-mini-twitter/auth/2');
        $this->assertEquals(4, $instance->execCallback());

    }
}
?>
