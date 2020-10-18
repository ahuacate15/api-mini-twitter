<?php
use PHPUnit\Framework\TestCase;

class userEntityTest extends TestCase {

    public function testInstance() {
        $this->assertInstanceOf(UserEntity::class, new UserEntity());
    }

    public function testSetQueryResult() {
        $entity = new UserEntity();
        $entity->setQueryResult(array(
            'id_user' => 1,
            'user_name' => 'admin',
            'email' => 'carlos.itca@gmail.com',
            'created_date' => '2020-09-09 12:00:21',
            'role' => 'ADMIN',
            'name' => 'carlos',
            'lastname' => 'menjivar',
            'photo_url' => '',
            'genre' => 'M'
        ));
        $this->assertEquals(1, $entity->idUser);
        $this->assertEquals('admin', $entity->userName);
        $this->assertEquals('carlos.itca@gmail.com', $entity->email);
        $this->assertEquals('2020-09-09 12:00:21', $entity->createdDate);
        $this->assertEquals('ADMIN', $entity->role);
        $this->assertEquals('carlos', $entity->name);
        $this->assertEquals('menjivar', $entity->lastname);
        $this->assertEquals('', $entity->photoUrl);
        $this->assertEquals('M', $entity->genre);
    }

    public function testSetQueryResultWithBadParams() {
        $entity = new UserEntity();
        $entity->setQueryResult(array());

        $this->assertEquals(null, $entity->idUser);
        $this->assertEquals(null, $entity->userName);
        $this->assertEquals(null, $entity->email);
        $this->assertEquals(null, $entity->createdDate);
        $this->assertEquals(null, $entity->role);
        $this->assertEquals(null, $entity->name);
        $this->assertEquals(null, $entity->lastname);
        $this->assertEquals(null, $entity->photoUrl);
        $this->assertEquals(null, $entity->genre);
    }

    public function testSetQueryResultWithFalseParam() {
        $entity = new UserEntity();
        $entity->setQueryResult(false);

        $this->assertEquals(null, $entity->idUser);
        $this->assertEquals(null, $entity->userName);
        $this->assertEquals(null, $entity->email);
        $this->assertEquals(null, $entity->createdDate);
        $this->assertEquals(null, $entity->role);
        $this->assertEquals(null, $entity->name);
        $this->assertEquals(null, $entity->lastname);
        $this->assertEquals(null, $entity->photoUrl);
        $this->assertEquals(null, $entity->genre);
    }

    public function testSetQueryResultWithNullParam() {
        $entity = new UserEntity();
        $entity->setQueryResult(null);

        $this->assertEquals(null, $entity->idUser);
        $this->assertEquals(null, $entity->userName);
        $this->assertEquals(null, $entity->email);
        $this->assertEquals(null, $entity->createdDate);
        $this->assertEquals(null, $entity->role);
        $this->assertEquals(null, $entity->name);
        $this->assertEquals(null, $entity->lastname);
        $this->assertEquals(null, $entity->photoUrl);
        $this->assertEquals(null, $entity->genre);
    }
}
?>
