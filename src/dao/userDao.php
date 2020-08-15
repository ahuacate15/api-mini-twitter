<?php
require_once __DIR__.'/iUserDao.php';
require_once __DIR__.'/../includes/connection.php';

class UserDao extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function saveUser(UserEntity $userEntity) {
        $sql =
            "insert into user(user_name, email, role, password_hash, created_date) ".
            "values (:userName, :email, :role, :passwordHash, now())";


        return $this->execute($sql, array(
            'userName' => $userEntity->userName,
            'email' => $userEntity->email,
            'role' => $userEntity->role,
            'passwordHash' => $userEntity->passwordHash
        ));
    }
}
?>
