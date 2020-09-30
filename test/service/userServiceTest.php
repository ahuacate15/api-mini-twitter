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
    public function testLogin($instance) {
        $this->assertEquals(ResponseHTTP::OK, $instance->login('admin', '12345')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->login('admin@test.gob', '12345')->statusCode);

        $object = $instance->login('admin', '12345');
        $this->assertArrayHasKey('message', $object->object);
        $this->assertArrayHasKey('user_name', $object->object);
        $this->assertArrayHasKey('email', $object->object);
        $this->assertArrayHasKey('jwt', $object->object);
    }

    /**
    * @depends testInstance
    */
    public function testLoginUserNotExist($instance) {
        $this->assertEquals(ResponseHTTP::NOT_FOUND, $instance->login('bad_user', '12345')->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testLoginBadPassword($instance) {
        $this->assertEquals(ResponseHTTP::UNAUTHORIZED, $instance->login('admin', 'bad_password')->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testSignup($instance) {

        $response = $instance->signup('carlos.menjivar', 'carlos@gmail.com', '12345');

        $userEntity = new UserEntity();
        $userEntity->userName = 'carlos.menjivar';
        $userEntity->email = 'carlos.itca@gmail.com';

        $jwt = new JwtSecurity();

        //valido el token generado al crear un usuario
        $this->assertNotFalse($jwt->validateToken($response->object['jwt']));

        //verifico que coincida el estado de la peticion
        $this->assertEquals(ResponseHTTP::CREATED, $response->statusCode);

        //elimino el token del objeto response, ya que es distinto al variar los microsegundos de creacion
        unset($response->object['jwt']);

        $this->assertEquals(array(
                'user_name' => 'carlos.menjivar',
                'email' => 'carlos.itca@gmail.com',
                'id_user' => 2,
                'created_date' => '2020-09-28 20:21:48'
            ),
            $response->object
        );

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
