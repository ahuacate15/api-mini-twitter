<?php
use PHPUnit\Framework\TestCase;

class RouteHTTPTest extends TestCase {

    public function testInstance() {
        $instance = new RouteHTTPTest();
        $this->assertInstanceOf(RouteHTTP::class, $instance);
        return $instance;
    }
}
?>
