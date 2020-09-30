<?php
use PHPUnit\Framework\TestCase;

class utilHTTPTest extends TestCase {

    public function testInstance() {
        $headers = array(
            'Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNjAxNDMwNzc2LCJleHAiOjE2MTAwNzA3NzYsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.k5oh8ZSHoFnganPSIvXM_mzU6YGzVnk7X3kAbZMQNYfYxX8rJwRLk7WWO9N-kPwN_cPWzlzL66Fr7Dsng8kPPA'
        );

        $instance = new UtilHTTP($headers);
        $this->assertInstanceOf(UtilHTTP::class, $instance);
        return $instance;
    }

    /**
    * @depends testInstance
    */
    public function testGetJWT($instance) {
        $this->assertEquals('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNjAxNDMwNzc2LCJleHAiOjE2MTAwNzA3NzYsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.k5oh8ZSHoFnganPSIvXM_mzU6YGzVnk7X3kAbZMQNYfYxX8rJwRLk7WWO9N-kPwN_cPWzlzL66Fr7Dsng8kPPA', $instance->getJWT());
    }

    public function testGetJWTWithoutJWT() {
        $utilHttp = new UtilHTTP(array());
        $this->assertNull($utilHttp->getJWT());
    }
}
?>
