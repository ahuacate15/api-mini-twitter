<?php
require_once __DIR__.'/iUserDao.php';
require_once __DIR__.'/../includes/connection.php';

class UserDao extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function findByUserNameOrEmail($key) {
        $sql = "select id_user, user_name, email, role, password_hash, created_date from user where user_name = :userName or email = :email";

        $this->setQuery($sql);
        $this->setString('userName', $key);
        $this->setString('email', $key);
        return $this->fetch();
    }

    public function saveUser(UserEntity $userEntity) {
        $sql =
            "insert into user(user_name, email, role, password_hash, created_date) ".
            "values (:userName, :email, :role, :passwordHash, now())";

        $this->setQuery($sql);
        $this->setString('userName', $userEntity->userName);
        $this->setString('email', $userEntity->email);
        $this->setString('role',  $userEntity->role);
        $this->setString('passwordHash', $userEntity->passwordHash);

        return $this->execute();
    }
}
?>
