<?php
require_once __DIR__.'/../entity/userEntity.php';

interface iUserDao {

    /**
    * @param key puede ser el nombre de usuario o el correo electronico
    */
    public function findByUserNameOrEmail($key);
    public function saveUser(UserEntity $userEntity);
}
?>
