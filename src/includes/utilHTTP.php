<?php
class UtilHTTP {

    private $headers;

    public function __construct($headers) {
        $this->headers = $headers;
    }

    public function getJWT() {
        if(isset($this->headers['Authorization'])) {
            return str_replace('bearer ', '', $this->headers['Authorization']);
        } else {
            return null;
        }
    }
}
?>
