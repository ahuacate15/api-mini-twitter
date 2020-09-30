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

    public function setToken($token) {
        $this->token = $token;
    }
}
?>
