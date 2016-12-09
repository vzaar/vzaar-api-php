<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Resources\HttpCurl;
    
    class HttpCurlTest extends VzaarTest {
    
    
        /**
         * @expectedException         VzaarApi\Exceptions\ConnectionEx
         */
        public function testHttpCurl_httpRequest() {
            
            $handler = new HttpCurl();
            
            $cfg['method'] = 'GET';
            $cfg['uri'] = 'test://api.vzaar.com/api/v2/encoding_presets';
            
            $result = $handler->httpRequest($cfg);
            
        }
    
    }
?>
