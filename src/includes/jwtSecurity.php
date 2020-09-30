<?php
require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../entity/userEntity.php';

use \Firebase\JWT\JWT;

class JwtSecurity {

    private $secret_key;
    private $issuer_clame;
    private $generation_date;
    private $expiration_date;

    public function __construct() {
        $this->secret_key = base64_decode(file_get_contents(__DIR__.'/jwt.pem'));
        $this->issuer_clame = "API-MINI-TWITTER";
        $this->generation_date = time();
        $this->expiration_date = time() + (60 * 60 * 24 * 100); /* expiracion de token dentro de 100 dias */
    }

    public function generateToken(UserEntity $userEntity) {
        $token = array(
            'iss' => $this->issuer_clame,
            'iat' => $this->generation_date,
            'exp' => $this->expiration_date,
            'data' => array(
                'userName' => $userEntity->userName,
                'email' => $userEntity->email
            )
        );

        return JWT::encode($token,  base64_decode($this->secret_key), 'HS512');
    }

    public function validateToken($token) {
        try {
            return JWT::decode($token, base64_decode($this->secret_key), array('HS512'));
        } catch(Exception $e) {
            return false;
        }
    }
}
?>
