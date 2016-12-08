<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Signature;
    
    class SignatureTest extends VzaarTest
    {
        public static $multipart;
        public static $single;
        
        public function testSignature_New()
        {
            
            $signature = new Signature();
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $signature->getClient());
            
        }
        
        public function testSignature_Parameter()
        {
            
            $client = new Client();
            $signature = new Signature($client);
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $signature->getClient());
            
        }
        
        public function testSignature_single()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/single', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                
                return \json_decode(self::$single);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $signature = Signature::single(null, $client);
            
            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature/single',$endpoint->getValue());
            
        }
        
        public function testSignature_multipart()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/multipart', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('filename', $recordRequest['recordData']);
                $this->assertArrayHasKey('filesize', $recordRequest['recordData']);
                
                return \json_decode(self::$multipart);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = array('filename' => 'FileName',
                           'filesize' => 10024);
            
            $signature = Signature::multipart($param, $client);
            
            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature/multipart',$endpoint->getValue());
            
        }
        
        public function testSignature_create_single()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/single', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('filename', $recordRequest['recordData']);
                $this->assertArrayHasKey('filesize', $recordRequest['recordData']);
                
                return \json_decode(self::$single);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            //create test file
            $filepath = 'single.mov';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            file_put_contents($filepath,'filecontent');
            
            $signature = Signature::create($filepath, $client);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            $this->assertNotTrue(isset($signature->parts));
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature/single',$endpoint->getValue());
        }
        
        public function testSignature_create_multipart()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/multipart', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('filename', $recordRequest['recordData']);
                $this->assertArrayHasKey('filesize', $recordRequest['recordData']);
                
                return \json_decode(self::$multipart);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            //create test file
            $filepath = 'multipart.mov';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            $content = '12345678901234567890123456789012345678901234567890';
            $content .= '12345678901234567890123456789012345678901234567890';
            
            do{
                file_put_contents($filepath,$content,FILE_APPEND);
                clearstatcache(true, $filepath);
            }
            while(filesize($filepath) < Client::MULTIPART_MIN_SIZE);
                
            
            $signature = Signature::create($filepath, $client);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            $this->assertTrue(isset($signature->parts));
            
            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/signature/multipart',$endpoint->getValue());
        }
        
        public static function setUpBeforeClass()
        {
            
            self::$multipart = <<<'EOD'
            {
                "data": {
                    "access_key_id": "<access-key-id>",
                    "key": "vzaar/vz9/1e8/source/vz91e80db09a494467b265f0c327950825/${filename}",
                    "acl": "private",
                    "policy": "<signed-policy-string>",
                    "signature": "<signature-string>",
                    "success_action_status": "201",
                    "content_type": "binary/octet-stream",
                    "guid": "vz91e80db09a494467b265f0c327950825",
                    "bucket": "vzaar-upload-development",
                    "upload_hostname": "https://vzaar-upload-development.s3.amazonaws.com",
                    "part_size": "16mb",
                    "part_size_in_bytes": 16777216,
                    "parts": 4
                }
            }
EOD;
            
            self::$single = <<<'EOD'
            {
                "data": {
                    "access_key_id": "<access-key-id>",
                    "key": "vzaar/vz9/1e8/source/vz91e80db09a494467b265f0c327950825/${filename}",
                    "acl": "private",
                    "policy": "<signed-policy-string>",
                    "signature": "<signature-string>",
                    "success_action_status": "201",
                    "content_type": "binary/octet-stream",
                    "guid": "vz91e80db09a494467b265f0c327950825",
                    "bucket": "vzaar-upload-development",
                    "upload_hostname": "https://vzaar-upload-development.s3.amazonaws.com"
                }
            }
EOD;
            
        }
    }
?>
