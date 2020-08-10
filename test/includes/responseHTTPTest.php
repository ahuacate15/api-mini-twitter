<?php
    use PHPUnit\Framework\TestCase;

    class responseHTTPTest extends TestCase {


        public function testJsonResponse() {
            $response = new ResponseHttp();
            $response->jsonResponse(200, array(
                'message' => 'message_test'
            ));
            $this->assertSame(200, $response->statusCode);
            $this->assertSame('message_test', $response->object['message']);
            $this->assertSame(ResponseHttp::JSON, $response->typeResponse);
        }

        public function testTestResponse(){
            $response = new ResponseHttp();
            $response->textResponse(200, '<b>texto</b>');
            $this->assertSame(200, $response->statusCode);
            $this->assertSame('<b>texto</b>', $response->object);
            $this->assertSame(ResponseHttp::TEXT, $response->typeResponse);
        }

    }
?>
