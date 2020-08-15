<?php
class ResponseHTTP {

    public $statusCode;
    public $object;
    public $typeResponse;

    public const JSON = 1;
    public const TEXT = 2;
    public const HTML = 3;
    public const PDF = 4;

    public const OK = 200;
    public const CREATED = 201;
    public const UNAUTHORIZED = 401;
    public const BAD_REQUEST = 400;
    public const CONFLICT = 403;
    public const NOT_FOUND = 404;
    public const INTERNAL_SERVER_ERROR = 500;

    /*
    * @param object : puede ser un string o un objeto cualquiera
    */
    public function jsonResponse($statusCode, $object) {
        $this->statusCode = $statusCode;
        $this->object = $object;
        $this->typeResponse = self::JSON;
        return $this;
    }

    /*
    * @param object : puede ser un string o un objeto cualquiera
    */
    public function textResponse($statusCode, $object) {
        $this->statusCode = $statusCode;
        $this->object = $object;
        $this->typeResponse = self::TEXT;
        return $this;
    }

    /*
    * @param object : codigo html
    */
    public function htmlResponse($statusCode, $object){
        $this->statusCode = $statusCode;
        $this->object = $object;
        $this->typeResponse = self::HTML;
        return $this;
    }

    /*
    * @param namePdf : nombre del archivo PDF que se descarga
    * @param urlFile : url absoluta del archivo PDF
    * @param $errObject :
    * @note : cuando el codigo es distinto a 200 (OK), se ejecuta el jsonResponse
    * notificando al navegador sobre el error
    */
    public function pdfResponse($statusCode, $namePdf, $urlFile) {
        $this->statusCode = $statusCode;
        $this->object = array(
            'nameFile' => $namePdf,
            'urlFile' => $urlFile
        );
        $this->typeResponse = self::PDF;
        return $this;
    }

    public function response() {
        http_response_code($this->statusCode);
        switch ($this->typeResponse) {
            case self::JSON:
                header('Content-type: application/json;charset=utf-8');
                echo json_encode($this->object, JSON_UNESCAPED_UNICODE);
                break;
            case self::TEXT:
                header('Content-type: text/plain');
                echo $this->object;
                break;
            case self::HTML:
                header('Content-type: text/html');
                echo $this->object;
                break;
            case self::PDF:
                header('Content-type: application/pdf');
                header('Content-Length: '.filesize($this->object['urlFile']));
                header('Content-Disposition: inline; filename="'.$this->object['nameFile'].'"');
                readfile($this->object['urlFile']);
                break;
        }

    }
}
?>
