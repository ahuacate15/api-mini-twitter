<?php
require_once __DIR__.'/../includes/responseHTTP.php';
require_once __DIR__.'/../includes/jwtSecurity.php';
require_once __DIR__.'/../dao/tweetDao.php';

class TweetService {

    private $tweetDao;
    private $response;
    private $jwt;
    private $token;

    public function __construct($tweetDao) {
        $this->tweetDao = $tweetDao;
        $this->response = new ResponseHTTP();
        $this->jwt = new JwtSecurity();
        $this->token = null;
    }

    public function findAll() {
        $jwtData = $this->jwt->validateToken($this->token);

        if(!$jwtData)
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'acceso denegado'));

        $tweetList = $this->tweetDao->findAll();
        return $this->response->jsonResponse(ResponseHTTP::OK, $tweetList);
    }

    public function create($message) {
        $jwtData = $this->jwt->validateToken($this->token);

        if(!$jwtData)
            return $this->response->jsonResponse(ResponseHTTP::UNAUTHORIZED, array('message' => 'acceso denegado'));

        try {
            //guardo el tweet
            $this->tweetDao->create($jwtData->data->idUser, $message);

            //recupero el registro
            $tweet = $this->tweetDao->findById($this->tweetDao->lastInsertId());
            return $this->response->jsonResponse(ResponseHTTP::OK, $tweet);
        } catch(Exception $e) {
            switch ($e->getCode()) {
                case Connection::FOREIGN_KEY_FAIL:
                    return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'error al recuperar los datos de tu usuario'));
                case Connection::DATA_TO_LONG:
                    return $this->response->jsonResponse(ResponseHTTP::BAD_REQUEST, array('message' => 'tu tweet no puede tener mas de 256 caracteres'));
                default:
                    return $this->response->jsonResponse(ResponseHTTP::INTERNAL_SERVER_ERROR, array('message' => 'error al registrar tweet'));
            }
        }
    }

    public function setToken($token) {
        $this->token = $token;
    }
}
?>
