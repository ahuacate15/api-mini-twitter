<?php
use PHPUnit\Framework\TestCase;

class userServiceTest extends TestCase {

    private function getMockToken() {
        $jwt = new JwtSecurity();
        $user = new UserEntity();
        $user->idUser = 1;
        $user->userName = 'admin';
        $user->email = 'admin@test.gob';

        $jwtToken = $jwt->generateToken($user);
        return $jwtToken;
    }

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
                'created_date' => '2020-09-28 20:21:48',
                'photo_url' => ''
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

    /**
    * @depends testInstance
    */
    public function testFindProfileByUserNameToken($instance) {
        $utilHttp = new utilHTTP(array('Authorization' => 'Bearer '.$this->getMockToken()));
        $instance->setToken($utilHttp->getJWT());
        $this->assertEquals(ResponseHTTP::OK, $instance->findProfileByUserNameToken()->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testFindProfileByUserNameTokenUnauthorized($instance) {
        $instance->setToken(null);
        $this->assertEquals(ResponseHTTP::UNAUTHORIZED, $instance->findProfileByUserNameToken()->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserField($instance) {
        $utilHttp = new utilHTTP(array('Authorization' => 'Bearer '.$this->getMockToken()));
        $instance->setToken($utilHttp->getJWT());
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('user_name', 'admin')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('email', 'admin@test.gob')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('name', 'carlos')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('lastname', 'menjivar')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('genre', 'M')->statusCode);

        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('name', '')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('name', null)->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('lastname', '')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('lastname', null)->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('genre', '')->statusCode);
        $this->assertEquals(ResponseHTTP::OK, $instance->updateUserField('genre', null)->statusCode);

        $this->assertIsArray($instance->updateUserField('user_name', 'admin')->object);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldEmptyUserName($instance) {
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('user_name', '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('user_name', null)->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldInvalidEmail($instance) {
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('email', 'admin@test')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('email', 'admin')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('email', 'admin@test.')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('email', null)->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldInvalidFields($instance) {
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('', '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('mail', '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField(null, '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField('', null)->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->updateUserField(null, null)->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldDuplicatedEmail($instance) {
        /*
        utilizo el token definido en el metodo getMockToken, para el usuario admin.
        el correo carlos.itca@gmail.com se encuentra en uso por otro usuario. el service captura el error
        */
        $response = $instance->updateUserField('email', 'carlos.itca@gmail.com');
        $this->assertEquals(ResponseHTTP::CONFLICT, $response->statusCode);
        $this->assertEquals('el correo está en uso', $response->object['message']);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldDuplicatedUserName($instance) {
        /*
        utilizo el token definido en el metodo getMockToken, para el usuario admin.
        el usuario carlos.menjivar se encuentra en uso por otro usuario. el service captura el error
        */
        $response = $instance->updateUserField('user_name', 'carlos.menjivar');
        $this->assertEquals(ResponseHTTP::CONFLICT, $response->statusCode);
        $this->assertEquals('el usuario está en uso', $response->object['message']);
    }

    /**
    * @depends testInstance
    */
    public function testUpdateUserFieldValueToLong($instance) {
        $response = $instance->updateUserField('user_name', 'carlos.eliseo.menjivar.ernesto.flores.beltran');
        $this->assertEquals(ResponseHTTP::INTERNAL_SERVER_ERROR, $response->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testChangePassword($instance) {
        $utilHttp = new utilHTTP(array('Authorization' => 'Bearer '.$this->getMockToken()));
        $instance->setToken($utilHttp->getJWT());

        /* credeciales correctas */
        $this->assertEquals(ResponseHTTP::OK, $instance->changePassword('12345', '12345')->statusCode);

        /* credenciales incorrectas */
        $this->assertEquals(ResponseHTTP::UNAUTHORIZED, $instance->changePassword('54321', '12345')->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testChangePasswordWithoutParams($instance) {
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->changePassword(null, null)->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->changePassword(null, '')->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->changePassword('', null)->statusCode);
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->changePassword('', '')->statusCode);
    }

}
?>
