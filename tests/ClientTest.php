<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Client;
    use VzaarApi\Recipe;
    use VzaarApi\CategoriesList;
    
    class ClentTest extends VzaarTest {
        
        public static $httpCode200;
        public static $httpCode200_category;
        public static $httpCode201;
        public static $httpCode201_100;
        public static $httpCode201_nobody;
        public static $httpCode204;
        public static $httpCode204_body;
        public static $httpCode400;
        public static $httpCode401;
        public static $httpCode401_nobody;
        public static $httpCode401_malformed;
        public static $httpCode403;
        public static $httpCode404;
        public static $httpCode422;
        public static $httpCode429;
        public static $httpCode500;
        public static $httpCode504;
    
        public function testClient_New() {
        
            $client = new Client();
            
            $this->assertEquals(Client::$client_id, $client->getClientId());
            $this->assertEquals(Client::$auth_token, $client->getAuthToken());
            $this->assertEquals(Client::$version, $client->getApiVersion());
            $this->assertEquals(Client::$urlAuth, $client->checkUrlAuth());
            
            $class = new \ReflectionClass($client);
            $httpHandler = $class->getProperty('httpHandler');
            $httpHandler->setAccessible(true);

            $this->assertInstanceOf(HttpCurl::class, $httpHandler->getValue($client));
            
        }
        
        public function testClient_New_param() {
            
            $param = ['client_id' => 'test_id',
            'auth_token' => 'test_token',
            'version' => 'v8',
            'urlAuth' => true];
            
            $client = new Client($param);
            
            $this->assertNotEquals(Client::$client_id, $client->getClientId());
            $this->assertNotEquals(Client::$auth_token, $client->getAuthToken());
            $this->assertNotEquals(Client::$version, $client->getApiVersion());
            $this->assertNotEquals(Client::$urlAuth, $client->checkUrlAuth());
            
            $this->assertEquals('test_id', $client->getClientId());
            $this->assertEquals('test_token', $client->getAuthToken());
            $this->assertEquals('v8', $client->getApiVersion());
            $this->assertEquals(true, $client->checkUrlAuth());
            
        }
        
        public function testClient_New_handler() {
            
            $handler = new HttpCurl();
            
            $client = new Client(null,$handler);
            
            $class = new \ReflectionClass($client);
            $httpHandler = $class->getProperty('httpHandler');
            $httpHandler->setAccessible(true);
            
            $this->assertInstanceOf(HttpCurl::class, $httpHandler->getValue($client));
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of VzaarApi\Resources\iHttpChannel
         */
        public function testClient_New_handler_Ex1() {
            
            //handler has to implement iHttpChannel
            $handler = array();
            
            $client = new Client(null,$handler);
            
        }
        
        public function testClient_httpResponse_200() {
            
            $callback = function($cfg) {
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
            $this->assertEquals(44, $recipe->id);
        
        }
        
        public function testClient_httpResponse_201() {
            
            $callback = function($cfg) {
                
                return self::$httpCode201;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
            $this->assertEquals(44, $recipe->id);
            
        }
        
        public function testClient_httpResponse_201_100() {
            
            $callback = function($cfg) {
                
                return self::$httpCode201_100;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
            $this->assertEquals(44, $recipe->id);
            
        }
        
        
        /**
         * @expectedException         VzaarApi\Exceptions\FunctionArgumentEx
         * @expectedExceptionMessage  Parameter should be instance of stdClass
         */
        public function testClient_httpResponse_201_Ex1() {
            
            $callback = function($cfg) {
                
                return self::$httpCode204;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage  Response data: JSON not valid - Syntax error
         */
        public function testClient_httpResponse_201_Ex2() {
            
            $callback = function($cfg) {
                
                return self::$httpCode201_nobody;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
        }
        
        
        public function testClient_httpResponse_204() {
            
            $callback = function($cfg) {
                
                switch($cfg['method']) {
                    case 'POST':
                        $result = self::$httpCode200;
                        break;
                    case 'DELETE':
                        $result = self::$httpCode204;
                }
                
                return $result;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
            $this->assertEquals(44, $recipe->id);
            $this->assertTrue(isset($recipe->name));
            
            $recipe->delete();
            
            $this->assertNotTrue(isset($recipe->id));
            $this->assertNotTrue(isset($recipe->name));
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage  No content expected with this response
         */
        public function testClient_httpResponse_204_body() {
            
            $callback = function($cfg) {
                
                switch($cfg['method']) {
                    case 'POST':
                        $result = self::$httpCode200;
                        break;
                    case 'DELETE':
                        $result = self::$httpCode204_body;
                }
                
                return $result;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            
            $params = array('name' => "Test Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = Recipe::create($params, $client);
            
            $this->assertEquals(44, $recipe->id);
            $this->assertTrue(isset($recipe->name));
            
            $recipe->delete();
            
            $this->assertNotTrue(isset($recipe->id));
            $this->assertNotTrue(isset($recipe->name));
            
        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 400 Details: .+/
         */
        public function testClient_httpResponse_400() {
            
            $callback = function($cfg) {
                
                return self::$httpCode400;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);

        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 401 Details: .+/
         */
        public function testClient_httpResponse_401() {
            
            $callback = function($cfg) {
                
                return self::$httpCode401;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        /**
         * @expectedException           VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage    Response data: JSON not valid - Syntax error 
         */
        public function testClient_httpResponse_401_nobody() {
            
            $callback = function($cfg) {
                
                return self::$httpCode401_nobody;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null, $handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        /**
         * @expectedException           VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage    Response data: response not correct
         */
        public function testClient_httpResponse_401_malformed() {
            
            $callback = function($cfg) {
                
                return self::$httpCode401_malformed;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 403 Details: .+/
         */
        public function testClient_httpResponse_403() {
            
            $callback = function($cfg) {
                
                return self::$httpCode403;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 404 Details: .+/
         */
        public function testClient_httpResponse_404() {
            
            $callback = function($cfg) {
                
                return self::$httpCode404;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 422 Details: .+/
         */
        public function testClient_httpResponse_422() {
            
            $callback = function($cfg) {
                
                return self::$httpCode422;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
            
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 429 Details: .+/
         */
        public function testClient_httpResponse_429() {
            
            $callback = function($cfg) {
                
                return self::$httpCode429;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
        }
        
        /**
         * @expectedException                VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessageRegExp  /HttpCode: 500 Details: .+/
         */
        public function testClient_httpResponse_500() {
            
            $callback = function($cfg) {
                
                return self::$httpCode500;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage  Unknown response from server. HttpCode: 504
         */
        public function testClient_httpResponse_504() {
            
            $callback = function($cfg) {
                
                return self::$httpCode504;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
        }
        
        
        /**
         * @expectedException         VzaarApi\Exceptions\ClientErrorEx
         * @expectedExceptionMessage  Unknown response from server. HttpCode: Unknown
         */
        public function testClient_httpResponse_empty() {
            
            $callback = function($cfg) {
                
                return ;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
        }
        
        
        public function testClient_clientSend_find() {
        
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertNotEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/ingest_recipes\/44$/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        public function testClient_clientSend_find_apiVersion() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertNotEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v8\/ingest_recipes\/44$/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = [
            'version' => 'v8'];
            
            $client = new Client($param,$handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        public function testClient_clientSend_find_authUrl() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/ingest_recipes\/44?/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                $this->assertRegExp('/client_id=.+/', $cfg['uri']);
                $this->assertRegExp('/auth_token=.+/', $cfg['uri']);
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = [
            'urlAuth' => true];
            
            $client = new Client($param, $handler);
            
            $recipe = Recipe::find(44, $client);
            
        }
        
        public function testClient_clientSend_save_authUrl() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                
                if($cfg['method'] == 'GET')
                    return self::$httpCode200;
                
                $this->assertNotEmpty($cfg['headers']);
                $this->assertNotEmpty($cfg['data']);
                
                $regex_name = '/"name":"New Name"/';
                $this->assertNotRegExp($regex_name, $cfg['data']);
                $regex_multipass = '/"multipass":true/';
                $this->assertRegExp($regex_multipass, $cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/ingest_recipes\/44?/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                $this->assertRegExp('/client_id=.+/', $cfg['uri']);
                $this->assertRegExp('/auth_token=.+/', $cfg['uri']);
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = [
            'urlAuth' => true];
            
            $client = new Client($param, $handler);
            
            $recipe = Recipe::find(44, $client);
            
            $recipe->name = "New Name";
            
            $bodyParam = array('multipass'=> true);
            
            $recipe->save($bodyParam);
            
        }
        
        public function testClient_clientSend_delete() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                
                if($cfg['method'] == 'GET')
                    return self::$httpCode200;
                
                $this->assertNotEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/ingest_recipes\/44$/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $recipe = Recipe::find(44, $client);
            
            $recipe->name = "New Name";
            
            $recipe->delete();
            
        }
        
        public function testClient_clientSend_paginate() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertNotEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/categories?/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                $this->assertRegExp('/order=asc/', $cfg['uri']);
                $this->assertRegExp('/page=11/', $cfg['uri']);
                $this->assertRegExp('/per_page=3/', $cfg['uri']);
                $this->assertNotRegExp('/client_id=.+/', $cfg['uri']);
                $this->assertNotRegExp('/auth_token=.+/', $cfg['uri']);
                
                return self::$httpCode200_category;
            };
        
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null,$handler);
            
            $query = array('order' => "asc",
                           'page' => 11,
                           'per_page' => 3);
            
            $categories = CategoriesList::paginate($query, $client);
            
        
        }
        
        public function testClient_clientSend_paginate_authUri() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/categories?/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                $this->assertRegExp('/order=asc/', $cfg['uri']);
                $this->assertRegExp('/page=11/', $cfg['uri']);
                $this->assertRegExp('/per_page=3/', $cfg['uri']);
                $this->assertRegExp('/client_id=.+/', $cfg['uri']);
                $this->assertRegExp('/auth_token=.+/', $cfg['uri']);
                
                return self::$httpCode200_category;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = [
            'urlAuth' => true];
            
            $client = new Client($param,$handler);
            
            $query = array('order' => "asc",
                           'page' => 11,
                           'per_page' => 3);
            
            $categories = CategoriesList::paginate($query, $client);
            
            
        }
        
        public function testClient_clientSend_subtree_authUri() {
            
            $callback = function($cfg) {
                
                $this->assertNotEmpty($cfg['method']);
                $this->assertEmpty($cfg['headers']);
                $this->assertEmpty($cfg['data']);
                
                $regex_uri = '/^https:\/\/api.vzaar.com\/api\/v2\/categories\/44\/subtree?/';
                $this->assertRegExp($regex_uri, $cfg['uri']);
                
                $this->assertRegExp('/order=asc/', $cfg['uri']);
                $this->assertRegExp('/page=11/', $cfg['uri']);
                $this->assertRegExp('/per_page=3/', $cfg['uri']);
                $this->assertRegExp('/client_id=.+/', $cfg['uri']);
                $this->assertRegExp('/auth_token=.+/', $cfg['uri']);
                
                return self::$httpCode200_category;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = [
            'urlAuth' => true];
            
            $client = new Client($param,$handler);
            
            $query = array('order' => "asc",
                           'page' => 11,
                           'per_page' => 3);
            
            $categories = CategoriesList::subtree(44, $query, $client);
            
            
        }
        
        public function testClient_check() {
        
            $callback = function($cfg) {
                
                return self::$httpCode200;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $client = new Client(null, $handler);
        
            $this->assertNull($client->checkRateLimit());
            $this->assertNull($client->checkRateRemaining());
            $this->assertNull($client->checkRateReset());
            
            $recipe = Recipe::find(44, $client);
            
            $this->assertNotNull($client->checkRateLimit());
            $this->assertNotNull($client->checkRateRemaining());
            $this->assertNotNull($client->checkRateReset());
        
        }

        
        
        public static function setUpBeforeClass()
        {
            
            /*
             * fixture HTTP 200
             */
            $status = 'HTTP/1.1 200 OK'."\r\n";
            $header[] = 'Cache-Control: max-age=0, private, must-revalidate';
            $header[] = 'Content-Type: application/json';
            $header[] = 'Date: Wed, 07 Dec 2016 11:13:54 GMT';
            $header[] = 'ETag: W/"57441156c7cc69de36af4f9702064402"';
            $header[] = 'Server: nginx';
            $header[] = 'X-RateLimit-Limit: 200';
            $header[] = 'X-RateLimit-Remaining: 197';
            $header[] = 'X-RateLimit-Reset: 1481109294';
            $header[] = 'X-RateLimit-Reset-In: 60 seconds';
            $header[] = 'X-Request-Id: 2595809b-9766-455f-beb4-61c8ab4b18d8';
            $header[] = 'X-Runtime: 0.082388';
            $header[] = 'Content-Length: 943';
            $header[] = 'Connection: keep-alive';
            $endheader = "\r\n\r\n";
            $body =<<<EOD
            {"data": {
                "id": 44,
                "name": "Test Recipe",
                "encoding_presets": [{
                    "id": 2,
                    "name": "SD"
                },
                {
                    "id": 3,
                    "name": "LD"
                }
                ]
            }
            }
EOD;
            
            self::$httpCode200 = array('httpCode' => 200,
                                   'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 200 - CategoryList
             */
            $body_category =<<<EOD
            {
                "data": [
                {
                    "id": 1,
                    "account_id": 1,
                    "user_id": 1,
                    "name": "Sciences",
                    "description": null,
                    "parent_id": null,
                    "depth": 0,
                    "node_children_count": 3,
                    "tree_children_count": 5,
                    "node_video_count": 3,
                    "tree_video_count": 6,
                    "created_at": "2015-04-06T22:03:24.000Z",
                    "updated_at": "2016-01-06T12:08:38.000Z"
                },
                {
                    "id": 2,
                    "account_id": 1,
                    "user_id": 1,
                    "name": "Chemistry",
                    "description": null,
                    "parent_id": 1,
                    "depth": 0,
                    "node_children_count": 3,
                    "tree_children_count": 5,
                    "node_video_count": 3,
                    "tree_video_count": 6,
                    "created_at": "2015-04-06T22:03:24.000Z",
                    "updated_at": "2016-01-06T12:08:38.000Z"
                }
                ],
                "meta": {
                    "links": {
                        "first": "http://api.vzaar.com/api/v2/categories?page=1",
                        "last": null,
                        "next": null,
                        "previous": null
                    },
                    "total_count": 2
                }
            }
EOD;
            
            self::$httpCode200_category = array('httpCode' => 200,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body_category);
            
            /*
             * fixture HTTP 201 - preceded with HTTP 100
             */
            $continue = 'HTTP/1.1 100 Continue'. $endheader;
            $status = 'HTTP/1.1 201 Created'."\r\n";
            
            self::$httpCode201_100 = array('httpCode' => 201,
                                       'httpResponse' => $continue. $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 201
             */
            $status = 'HTTP/1.1 201 Created'."\r\n";
            
            self::$httpCode201 = array('httpCode' => 201,
                                   'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 201 - no body
             */
            
            self::$httpCode201_nobody = array('httpCode' => 201,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader);
            
            /*
             * fixture HTTP 204
             */
            $status = 'HTTP/1.1 204 No Content'."\r\n";
            
            self::$httpCode204 = array('httpCode' => 204,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader);
            
            /*
             * fixture HTTP 204 - with content
             */
            $status = 'HTTP/1.1 204 No Content'."\r\n";
            
            self::$httpCode204_body = array('httpCode' => 204,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 400
             */
            $status = 'HTTP/1.1 400 Bad Request'."\r\n";
            $header[] = 'Cache-Control: no-cache';
            $header[] = 'Content-Type: application/json';
            $header[] = 'Date: Wed, 07 Dec 2016 11:13:54 GMT';
            $header[] = 'ETag: W/"57441156c7cc69de36af4f9702064402"';
            $header[] = 'Server: nginx';
            $header[] = 'X-Request-Id: 2595809b-9766-455f-beb4-61c8ab4b18d8';
            $header[] = 'X-Runtime: 0.082388';
            $header[] = 'Content-Length: 943';
            $header[] = 'Connection: keep-alive';
            $endheader = "\r\n\r\n";
            $body =<<<EOD
            {
                "errors": [
                {
                    "message": "Error message",
                    "detail": "Error detail"
                }
                ]
            }
EOD;
            
            self::$httpCode400 = array('httpCode' => 400,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            
            /*
             * fixture HTTP 401
             */
            $status = 'HTTP/1.1 401 Unauthorized'."\r\n";
            
            self::$httpCode401 = array('httpCode' => 401,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 401 - no body returned
             */
            $body_401_nobody = '';
            
            self::$httpCode401_nobody = array('httpCode' => 401,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body_401_nobody);
            
            /*
             * fixture HTTP 401 - malformed body
             */
            $body_401_malformed =<<<EOD
            {
                "anything": [
                {
                    "message": "Error message",
                    "detail": "Error detail"
                }
                ]
            }
EOD;
            
            self::$httpCode401_malformed = array('httpCode' => 401,
                                           'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body_401_malformed);
            
            /*
             * fixture HTTP 403
             */
            $status = 'HTTP/1.1 403 Forbidden'."\r\n";
            
            self::$httpCode403 = array('httpCode' => 403,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 404
             */
            $status = 'HTTP/1.1 404 Not Found'."\r\n";
            
            self::$httpCode404 = array('httpCode' => 404,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 422
             */
            $status = 'HTTP/1.1 422 Unprocessable Entity'."\r\n";
            
            self::$httpCode422 = array('httpCode' => 422,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 429
             */
            $status = 'HTTP/1.1 429 Too many Requests'."\r\n";
            
            self::$httpCode429 = array('httpCode' => 429,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 500
             */
            $status = 'HTTP/1.1 500 Server error'."\r\n";
            
            self::$httpCode500 = array('httpCode' => 500,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            /*
             * fixture HTTP 504
             */
            $status = 'HTTP/1.1 504 Gateway Time-out'."\r\n";
            
            self::$httpCode504 = array('httpCode' => 504,
                                       'httpResponse' => $status. \implode("\r\n", $header). $endheader. $body);
            
            
        }
    
    }

?>
