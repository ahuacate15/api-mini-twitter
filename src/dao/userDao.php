<?php
require_once __DIR__.'/iUserDao.php';
require_once __DIR__.'/../includes/connection.php';

class UserDao extends Connection implements iUserDao{

    public function __construct() {
        parent::__construct();
    }

    public function findByUserNameOrEmail($key) {
        $sql =
            "select ".
            "   u.id_user, u.user_name, u.email, ".
            "   u.role, u.password_hash, u.created_date, ".
            "   coalesce(ud.photo_url, '') as photo_url ".
            "from user u ".
            "left join user_data ud on u.id_user = ud.id_user ".
            "where user_name = :userName or email = :email";

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

    public function findProfileById($idUser) {
        $sql =
            "select ".
            "	u.id_user, u.user_name, u.created_date, u.email, u.role, ".
            "	coalesce(us.name, '') as name, coalesce(us.lastname, '') as lastname, ".
            "	coalesce(us.photo_url, '') as photo_url, ".
            "	coalesce(us.genre, '') as genre ".
            "from user u ".
            "left join user_data us on u.id_user = us.id_user ".
            "where u.id_user = :idUser";
        $this->setQuery($sql);
        $this->setInteger('idUser', $idUser);
        return $this->fetch();
    }

    public function update(UserEntity $userEntity) {
        $sql =
            "update user set ".
            "   user_name = :userName, email = :email, role = :role ".
            "where id_user = :idUser";
        $this->setQuery($sql);
        $this->setString('userName', $userEntity->userName);
        $this->setString('email', $userEntity->email);
        $this->setString('role', $userEntity->role);
        $this->setInteger('idUser', $userEntity->idUser);

        $code = $this->execute();
        if($code != Connection::OK) {
            throw new \Exception("error al ejecutar la consulta", $code);
        }

        $sql =
            "replace into user_data set ".
            "   id_user = :idUser, ".
            "   name = :name, ".
            "   lastname = :lastname, ".
            "   photo_url = :photoUrl, ".
            "   genre = :genre";
        $this->setQuery($sql);
        $this->setString('name', $userEntity->name);
        $this->setString('lastname', $userEntity->lastname);
        $this->setString('photoUrl', $userEntity->photoUrl);
        $this->setString('genre', $userEntity->genre);
        $this->setString('idUser', $userEntity->idUser);

        $code = $this->execute();

        if($code != Connection::OK) {
            throw new \Exception("error al ejecutar la consulta", $code);
        }

        return $code;
    }
}
?>
