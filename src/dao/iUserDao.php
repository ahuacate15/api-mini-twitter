<?php
require_once __DIR__.'/../entity/userEntity.php';

interface iUserDao {

    public function saveUser(UserEntity $userEntity);
}
?>
