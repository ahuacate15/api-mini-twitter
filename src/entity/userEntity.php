<?php
class UserEntity {

    public $idUser;
    public $userName;
    public $email;
    public $createdDate;
    public $role;
    public $passwordHash;
    public $name;
    public $lastname;
    public $photoUrl;
    public $genre;

    public function __construct() {
        $this->userName = '';
        $this->email = '';
        $this->role = 'USER';
        $this->passwordHash = '';
        $this->createdDate = date('Y-m-d H:i:s');
    }

    /**
    * parseo un array asociativo a un objeto UserEntity.
    * asumo que los nombres de campo son separador por _
    */
    public function setQueryResult($data) {
        $this->idUser = isset($data['id_user']) ? $data['id_user'] : null;
        $this->userName = isset($data['user_name']) ? $data['user_name'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->createdDate = isset($data['created_date']) ? $data['created_date'] : null;
        $this->role = isset($data['role']) ? $data['role'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $this->photoUrl = isset($data['photo_url']) ? $data['photo_url'] : null;
        $this->genre = isset($data['genre']) ? $data['genre'] : null;
    }
}
?>
