<?php
require_once __DIR__.'/../dao/iUserDao.php';
require_once __DIR__.'/../includes/connection.php';

class MockUserDao {

    public function saveUser(UserEntity $userEntity) {

        if($userEntity->userName == 'admin')
            return Connection::DUPLICATE_ROW;
        if($userEntity->email == 'admin@test.gob')
            return Connection::DUPLICATE_ROW;
        return Connection::OK;

    }
}
?>
