<?php
use PHPUnit\Framework\TestCase;

class tweetServiceTest extends TestCase {

    public function testInstance() {
        $instance = new TweetService(new MockTweetDao());
        $this->assertInstanceOf(TweetService::class, $instance);
        return $instance;
    }

    /**
    * @depends testInstance
    */
    public function testFindAllUnauthorized($instance) {
        $this->assertEquals(ResponseHTTP::UNAUTHORIZED, $instance->findAll()->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testFindAll($instance) {
        $headers = array(
            'Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNjAxNDMwNzc2LCJleHAiOjE2MTAwNzA3NzYsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.k5oh8ZSHoFnganPSIvXM_mzU6YGzVnk7X3kAbZMQNYfYxX8rJwRLk7WWO9N-kPwN_cPWzlzL66Fr7Dsng8kPPA'
        );
        $utilHttp = new utilHTTP($headers);
        $instance->setToken($utilHttp->getJWT());

        $this->assertEquals(ResponseHTTP::OK, $instance->findAll()->statusCode);
        $this->assertIsArray($instance->findAll()->object);
    }

}
?>
