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
                $this->assertEquals('/signature/single/2', $recordRequest['endpoint']);

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

            $this->assertEquals('/signature/single/2',$endpoint->getValue());

        }

        public function testSignature_multipart()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/multipart/2', $recordRequest['endpoint']);

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

            $this->assertEquals('/signature/multipart/2',$endpoint->getValue());

        }

        public function testSignature_create_single()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/single/2', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('filename', $recordRequest['recordData']);
                $this->assertArrayHasKey('filesize', $recordRequest['recordData']);

                return \json_decode(self::$single);
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $filepath = 'tests/fixtures/movie-1mb.mp4';

            $signature = Signature::create($filepath, $client);

            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            $this->assertNotTrue(isset($signature->parts));

            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);

            $this->assertEquals('/signature/single/2',$endpoint->getValue());
        }

        public function testSignature_create_multipart()
        {
            $callback = function($recordRequest) {

                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/signature/multipart/2', $recordRequest['endpoint']);

                $this->assertEmpty($recordRequest['recordPath']);

                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('filename', $recordRequest['recordData']);
                $this->assertArrayHasKey('filesize', $recordRequest['recordData']);

                return \json_decode(self::$multipart);
            };

            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));

            $filepath = 'tests/fixtures/movie-5mb.mp4';

            $signature = Signature::create($filepath, $client);

            $this->assertInstanceOf(Signature::class,$signature);
            $this->assertNotNull($signature->guid);
            $this->assertTrue(isset($signature->parts));

            $class = new \ReflectionClass($signature);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);

            $this->assertEquals('/signature/multipart/2',$endpoint->getValue());
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
