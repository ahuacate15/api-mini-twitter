<?php
use PHPUnit\Framework\TestCase;

class tweetServiceTest extends TestCase {

    private function getMockToken() {
        $jwt = new JwtSecurity();
        $user = new UserEntity();
        $user->idUser = 1;
        $user->userName = 'admin';
        $user->email = 'admin@test.gob';

        $jwtToken = $jwt->generateToken($user);
        return $jwtToken;
    }

    private function getBadMockToken() {
        $jwt = new JwtSecurity();
        $user = new UserEntity();
        $user->idUser = 100;
        $user->userName = 'not_exist';
        $user->email = 'not_exist';

        $jwtToken = $jwt->generateToken($user);
        return $jwtToken;
    }

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
        $headers = array('Authorization' => 'Bearer '.$this->getMockToken());
        $utilHttp = new utilHTTP($headers);
        $instance->setToken($utilHttp->getJWT());

        $this->assertEquals(ResponseHTTP::OK, $instance->findAll()->statusCode);
        $this->assertIsArray($instance->findAll()->object);
    }

    /**
    * @depends testInstance
    */
    public function testCreateUnauthorized($instance) {
        $instance->setToken(null);
        $this->assertEquals(ResponseHTTP::UNAUTHORIZED, $instance->create("tweet message")->statusCode);
    }

    /**
    * @depends testInstance
    */
    public function testCreate($instance) {
        $headers = array('Authorization' => 'Bearer '.$this->getMockToken());
        $utilHttp = new utilHTTP($headers);
        $instance->setToken($utilHttp->getJWT());
        $this->assertEquals(ResponseHTTP::OK, $instance->create("tweet message")->statusCode);
        $this->assertIsArray($instance->create("tweet message")->object);
    }

    /**
    * @depends testInstance
    */
    public function testCreateForeignKeyError($instance) {
        $headers = array('Authorization' => 'Bearer '.$this->getBadMockToken());
        $utilHttp = new utilHTTP($headers);
        $instance->setToken($utilHttp->getJWT());
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->create("tweet message")->statusCode);
        $this->assertEquals("error al recuperar los datos de tu usuario", $instance->create("tweet message")->object['message']);
    }

    /**
    * @depends testInstance
    */
    public function testCreateToLongTweet($instance) {
        $longMessage =
            "Siddharta regaló su túnica a un pobre de la carretera. Desde entonces, sólo vistió el taparrabos y la descosida capa de color tierra. ".
            "Comió solamente una vez al día y jamás alimentos cocinados. Ayunó durante quince días. Ayunó durante veintiocho días. ".
            "La carne desapareció de sus muslos y mejillas. Ardientes sueños oscilaban en sus ojos dilatados; ".
            "en sus dedos huesudos crecían largas uñas, y del mentón le nacía una barba reseca y despeinada";
        $headers = array('Authorization' => 'Bearer '.$this->getMockToken());
        $utilHttp = new utilHTTP($headers);
        $instance->setToken($utilHttp->getJWT());
        $this->assertEquals(ResponseHTTP::BAD_REQUEST, $instance->create($longMessage)->statusCode);
        $this->assertEquals("tu tweet no puede tener mas de 256 caracteres", $instance->create($longMessage)->object['message']);
    }

}
?>
