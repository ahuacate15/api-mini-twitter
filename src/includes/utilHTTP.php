<?php
class UtilHTTP {

    private $headers;

    public function __construct($headers) {
        $this->headers = $headers;
    }

    public function getJWT() {
        if(isset($this->headers['Authorization'])) {
            $token = str_replace('bearer ', '', $this->headers['Authorization']);
            $token = str_replace('Bearer ', '', $token);
            return $token;
        } else {
            return null;
        }
    }
}
?>
