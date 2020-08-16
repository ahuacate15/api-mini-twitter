<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/jwtSecurity.php';
require_once __DIR__.'/../dao/userDao.php';

class UserService {

    private $userDao;
    private $response;
    private $jwt;

    public function __construct($userDao) {
        $this->userDao = $userDao;
        $this->response = new ResponseHTTP();
        $this->jwt = new JwtSecurity();
    }

    /**
    * @param key es el nombre de usuario o correo electronico
    */
    public function login($key, $password) {
        $user = $this->userDao->findByUserNameOrEmail($key);

        if(!$user)
            return $this->response->jsonResponse(ResponseHTTP::NOT_FOUND, array('message' => 'El usuario no existe'));

        if(password_verify($password, $user['password_hash'])) {
            $userEntity = new UserEntity();
            $userEntity->userName = $user['user_name'];
            $userEntity->email = $user['email'];

            $jwt = $this->jwt->generateToken($userEntity);

            return $this->response->jsonResponse(ResponseHTTP::OK, array(
                'message' => 'inicio de sesion correcto',
                'userName' => $userEntity->userName,
                'email' => $userEntity->email,
                'jwt' => $jwt
            ));
        } else {
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'Credenciales incorrectas'));
        }

    }

    public function signup($userName, $email, $password) {

        /* validacion de parametros */
        if($userName == '' || $userName == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el usuario');

        if($email == '' || $email == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el correo');

        if($password == '' || $password == null)
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'Falta el password');

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $this->response->textResponse(ResponseHTTP::BAD_REQUEST, 'El formato de correo es incorrecto');

        $userEntity = new UserEntity();
        $userEntity->userName = $userName;
        $userEntity->email = $email;
        $userEntity->passwordHash = password_hash($password, CRYPT_BLOWFISH);

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
