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
}
?>
