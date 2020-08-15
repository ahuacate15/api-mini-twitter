<?php
class UserEntity {

    public $idUser;
    public $userName;
    public $email;
    public $role;
    public $passwordHash;
    public $createdDate;

    public function __construct() {
        $this->userName = '';
        $this->emal = '';
        $this->role = 'USER';
        $this->passwordHash = '';
        $this->createdDate = date('Y-m-d H:i:s');
    }
}
?>
