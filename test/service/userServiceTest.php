<?php
use PHPUnit\Framework\TestCase;

class userServiceTest extends TestCase {

    public function testInstance() {
        $instance = new UserService(new MockUserDao());
        $this->assertInstanceOf(UserService::class, $instance);
        return $instance;
    }

    /**
    * @depends testInstance
    */
    public function testSignup($instance) {
        $this->assertEquals(ResponseHTTP::CREATED, $instance->signup('carlos.menjivar', 'carlos@gmail.com', '12345')->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testSignupBadParams($instance) {
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('', 'carlos@gmail.com', '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup(null, 'carlos@gmail.com', '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('carlos.menjivar', '', '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('carlos.menjivar', null, '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('carlos.menjivar', 'carlos@gmail.com', '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('carlos.menjivar', 'carlos@gmail.com', null)->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->signup('carlos.menjivar', 'invalid_mail', '12345')->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testSignupDuplicatedUserAndEmail($instance) {
        $this->assertEquals(ResponseHTTP::CONFLICT, $instance->signup('admin', 'genericmail@gmail.com', '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::CONFLICT, $instance->signup('genericuser', 'admin@test.gob', '12345')->statusCode);
    }
}
?>
