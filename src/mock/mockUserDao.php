<?php
require_once __DIR__.'/../dao/iUserDao.php';
require_once __DIR__.'/../includes/connection.php';

class MockUserDao {

    public function findByUserNameOrEmail($key) {

        $data = array(
            'admin' => array(
                'id_user' => 1,
                'user_name' => 'admin',
                'email' => 'admin@test.gob',
                'password_hash' => '$2y$10$IFAzzGLl7Gfz2xhEMrcaQ.irxDk7KaYtauS9NcxlXjaWNOF2dqmmK',
                'created_date' => date('Y-m-d H:i:s')
            ),
            'admin@test.gob' => array(
                'id_user' => 1,
                'user_name' => 'admin',
                'email' => 'admin@test.gob',
                'password_hash' => '$2y$10$IFAzzGLl7Gfz2xhEMrcaQ.irxDk7KaYtauS9NcxlXjaWNOF2dqmmK',
                'created_date' => date('Y-m-d H:i:s')
            ),
            'carlos.menjivar' => array(
                'id_user' => 2,
                'user_name' => 'carlos.menjivar',
                'email' => 'carlos.itca@gmail.com',
                'password_hash' => '$2y$10$IFAzzGLl7Gfz2xhEMrcaQ.irxDk7KaYtauS9NcxlXjaWNOF2dqmmK',
                'created_date' => '2020-09-28 20:21:48'
            )
        );

        return isset($data[$key]) ? $data[$key] : [];
    }

    public function saveUser(UserEntity $userEntity) {

        if($userEntity->userName == 'admin')
            return Connection::DUPLICATE_ROW;
        if($userEntity->email == 'admin@test.gob')
            return Connection::DUPLICATE_ROW;
        return Connection::OK;
    }

    public function findProfileById($idUser) {
        $data = array(
            1 => array(
                'id_user' => 1,
                'user_name' => 'admin',
                'created_date' => '2020-08-15 23:14:17',
                'name' => 'carlos',
                'lastname' => 'menjivar',
                'photo_url' => '',
                'genre' => '',
                'email' => 'admin@test.gob',
                'role' => 'ADMIN'
            )
        );

        return isset($data[$idUser]) ? $data[$idUser] : [];
    }

    public function update(UserEntity $userEntity) {
        if($userEntity->email == 'carlos.itca@gmail.com') {
            throw new \Exception("error al ejecutar la consulta", Connection::DUPLICATE_ROW);
        }
        if($userEntity->userName == 'carlos.menjivar') {
            throw new \Exception("error al ejecutar la consulta", Connection::DUPLICATE_ROW);
        }
        if(strlen($userEntity->userName) > 35) {
            throw new \Exception("error al ejecutar la consulta", Connection::DATA_TO_LONG);
        }
    }
}
?>
