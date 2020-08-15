<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../dao/userDao.php';

class UserService {

    private $response;
    private $userDao;

    public function __construct($userDao) {
        $this->response = new ResponseHTTP();
        $this->userDao = $userDao;
    }

    public function signup($userName, $email, $password) {

        /* validacion de parametros */
        if($userName == '' || $userName == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el usuario');

        if($email == '' || $email == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el correo');

        if($password == '' || $password == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el password');

        $userEntity = new UserEntity();
        $userEntity->userName = $userName;
        $userEntity->email = $email;
        $userEntity->passwordHash = $password;

        switch ($this->userDao->saveUser($userEntity)) {
            case Connection::OK:
                return $this->response->textResponse(ResponseHTTP::CREATED, 'ok');
                break;
            case Connection::DUPLICATE_ROW:
                return $this->response->textResponse(ResponseHTTP::CONFLICT, 'el usuario o correo están en uso');
                break;
            default:
                return $this->response->textResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, 'error al registrar usuario');
        }
    }
}
?>
