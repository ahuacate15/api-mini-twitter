<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/jwtSecurity.php';
require_once __DIR__.'/../dao/userDao.php';

class UserService {

    private $userDao;
    private $response;
    private $jwt;
    private $token;

    public function __construct($userDao) {
        $this->userDao = $userDao;
        $this->response = new ResponseHTTP();
        $this->jwt = new JwtSecurity();
        $this->token = null;
    }

    /**
    * @param key es el nombre de usuario o correo electronico
    */
    public function login($key, $password) {
        $user = $this->userDao->findByUserNameOrEmail($key);

        if(!$user) {
            return $this->response->jsonResponse(ResponseHTTP::NOT_FOUND, array('message' => 'el usuario no existe'));
        }

        if(password_verify($password, $user['password_hash'])) {
            $userEntity = new UserEntity();
            $userEntity->idUser = $user['id_user'];
            $userEntity->userName = $user['user_name'];
            $userEntity->email = $user['email'];

            $jwt = $this->jwt->generateToken($userEntity);

            return $this->response->jsonResponse(ResponseHTTP::OK, array(
                'message' => 'inicio de sesion correcto',
                'user_name' => $userEntity->userName,
                'email' => $userEntity->email,
                'jwt' => $jwt
            ));
        } else {
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'credenciales incorrectas'));
        }

    }

    public function signup($userName, $email, $password) {

        /* validacion de parametros */
        if($userName == '' || $userName == null)
            return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'falta el usuario'));

        if($email == '' || $email == null)
            return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'falta el correo'));

        if($password == '' || $password == null)
            return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'falta el password'));

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'el formato de correo es incorrecto'));

        $userEntity = new UserEntity();
        $userEntity->userName = $userName;
        $userEntity->email = $email;
        $userEntity->passwordHash = password_hash($password, CRYPT_BLOWFISH);
        switch ($this->userDao->saveUser($userEntity)) {
            case Connection::OK:
                $createdUser = $this->userDao->findByUserNameOrEmail($userName);
                $createdUser['jwt'] = $this->jwt->generateToken($userEntity);
                unset($createdUser['password_hash']);
                return $this->response->jsonResponse(ResponseHTTP::CREATED, $createdUser);
                break;
            case Connection::DUPLICATE_ROW:
                return $this->response->jsonResponse(ResponseHTTP::CONFLICT, array('message' => 'el usuario o correo están en uso'));
                break;
            default:
                return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al registrar usuario'));
        }
    }

    /**
    * busco el perfil del usuario incluido en el token de seguridad
    */
    public function findProfileByUserNameToken() {
        $jwtData = $this->jwt->validateToken($this->token);

        if(!$jwtData) {
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'acceso denegado'));
        }

        $userProfile = $this->userDao->findProfileById($jwtData->data->idUser);
        if(!$userProfile) {
            return $this->response->jsonResponse(ResponseHTTP::NOT_FOUND, array('message' => 'el usuario no existe'));
        } else {
            return $this->response->jsonResponse(ResponseHTTP::OK, $userProfile);
        }
    }

    /**
    * actualizo el perfil de un usuario, un campo a la vez
    */
    public function updateUserField($field, $value) {
        $jwtData = $this->jwt->validateToken($this->token);

        if(!$jwtData) {
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'acceso denegado'));
        }

        $user = new UserEntity();
        $user->setQueryResult($this->userDao->findProfileById($jwtData->data->idUser));

        switch ($field) {
            case 'user_name':
                if($value != null && $value != '') {
                    $user->userName = $value;
                } else {
                    return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'el nombre de usuario es requerido'));
                }

                break;
            case 'email':
                if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $user->email = $value;
                } else {
                    return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'el formato de correo es incorrecto'));
                }
            case 'name':
                $user->name = $value;
                break;
            case 'lastname':
                $user->lastname = $value;
                break;
            case 'genre':
                $user->genre = $value;
                break;
            default:
                return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'el campo no existe'));

        }

        try {
            $code = $this->userDao->update($user);
            $userProfile = $this->userDao->findProfileById($jwtData->data->idUser);
            return $this->response->jsonResponse(ResponseHTTP::OK, $userProfile);
        } catch(Exception $e) {
            switch ($e->getCode()) {
                case Connection::DUPLICATE_ROW:
                    if($field == 'user_name') {
                        return $this->response->jsonResponse(ResponseHTTP::CONFLICT, array('message' => 'el usuario está en uso'));
                    } else if($field == 'email') {
                        return $this->response->jsonResponse(ResponseHTTP::CONFLICT, array('message' => 'el correo está en uso'));
                    } else {
                        return $this->response->jsonResponse(ResponseHTTP::CONFLICT, array('message' => 'el campo está en uso'));
                    }
                case connection::DATA_TO_LONG:
                    return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'el campo es demasiado grande'));
                default:
                    return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al actualizar campo'));
            }
        }
    }

    public function uploadPhoto($image) {

        $jwtData = $this->jwt->validateToken($this->token);

        if(!$jwtData) {
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'acceso denegado'));
        }

        if($image == null) {
            return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'error al enviar la imagen'));
        }

        $uploads_dir = __DIR__.'/../../uploads/';
        $tmp_name = $image["tmp_name"];
        $name = basename($image["name"]);

        /*
        creo una carpeta por cada usuario, utilizando el ID del mismo ej. uploads/25/ o uploads/12/
        con este if, verifico que la carpeta haya sido creada (si no existe) sin problemas
        */
        
        if(!file_exists($uploads_dir.'/'.$jwtData->data->idUser) && !mkdir($uploads_dir.'/'.$jwtData->data->idUser)) {
            return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al almacenar la imagen'));
        }

        if(move_uploaded_file($tmp_name, $uploads_dir.'/'.$jwtData->data->idUser.'/'.$name)) {

            $user = new UserEntity();
            $user->setQueryResult($this->userDao->findProfileById($jwtData->data->idUser));

            //la url no incluye el directorio uploads/
            $user->photoUrl = 'uploads/'.$jwtData->data->idUser.'/'.$name;

            try {
                $this->userDao->update($user);
                $userProfile = $this->userDao->findProfileById($jwtData->data->idUser);
                return $this->response->jsonResponse(ResponseHTTP::OK, $userProfile);
            } catch(Exception $e) {
                return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al actualizar tu informacion'));
            }


        } else {
            return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al subir imagen'));
        }


    }

    public function setToken($token) {
        $this->token = $token;
    }
}
?>
