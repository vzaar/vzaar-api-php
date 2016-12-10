<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Resources\S3Client;
    use VzaarApi\Exceptions\S3uploadEx;
    use VzaarApi\Client;
    use VzaarApi\Video;
    use VzaarApi\Signature;
    
    class S3ClentTest extends VzaarTest {
        
        public static $single;
        public static $multipart;
        public static $lookup;
        public static $httpCode201;
        public static $httpCode404;
        
        public function testS3Client_New() {
            
            $client = new S3Client();
            
            $class = new \ReflectionClass($client);
            $httpHandler = $class->getProperty('httpHandler');
            $httpHandler->setAccessible(true);
            
            $this->assertInstanceOf(HttpCurl::class, $httpHandler->getValue($client));
            
        }
        
        public function testS3Client_New_param() {
            
            $handler = new HttpCurl();
            $client = new S3Client($handler);
            
            $class = new \ReflectionClass($client);
            $httpHandler = $class->getProperty('httpHandler');
            $httpHandler->setAccessible(true);
            
            $this->assertInstanceOf(HttpCurl::class, $httpHandler->getValue($client));
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  Parameter should be instance of VzaarApi\Resources\IHttpChannel
         */
        public function testS3Client_New_Ex1() {
            
            $handler = new \stdClass();
            $client = new S3Client($handler);
            
        }
        
        public function testS3Client_uploadFile_videoCreate_small() {
            
            //mock HttpCurl
            $callback_handler = function($cfg) {
                
                $this->assertEquals('POST', $cfg['method']);
                $this->assertEquals('Enclosure-Type: multipart/form-data', $cfg['headers'][0]);

                $this->assertEquals('https://vzaar-upload-development.s3.amazonaws.com', $cfg['uri']);
                
                $this->assertArrayNotHasKey('chunk', $cfg['data']);
                
                $regex_key = '/^.+\.\d{1,}$/';
                $this->assertNotRegExp($regex_key, $cfg['data']['key']);
                
                $this->assertInstanceOf(\CURLFile::class, $cfg['data']['file']);
                
                return self::$httpCode201;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $filepath = 'movie.mp4';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            file_put_contents($filepath,'filecontent');
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $param = array('filepath' => $filepath);
            
            $video = Video::create($param, $client, $s3client);
            
            $this->assertTrue(isset($video->id));
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
        }
        
        public function testS3Client_uploadFile_videoCreate_medium() {
            
            //mock HttpCurl
            $callback_handler = function($cfg) {
                
                $this->assertEquals('POST', $cfg['method']);
                $this->assertEquals('Enclosure-Type: multipart/form-data', $cfg['headers'][0]);
                
                $this->assertEquals('https://vzaar-upload-development.s3.amazonaws.com', $cfg['uri']);
                
                $this->assertArrayHasKey('chunk', $cfg['data']);
                
                $regex_key = '/^.+\.\d{1,}$/';
                $this->assertRegExp($regex_key, $cfg['data']['key']);
                
                $regex_file = '/1234567890/';
                $this->assertRegExp($regex_file, $cfg['data']['file']);
                
                return self::$httpCode201;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $filepath = 'movie.mp4';
            
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
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $param = array('filepath' => $filepath);
            
            $video = Video::create($param, $client, $s3client);
            
            $this->assertTrue(isset($video->id));
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\ArgumentTypeEx
         * @expectedExceptionMessage  File does not exist: notexisting.file
         */
        public function testS3Client_uploadFile_Ex1() {

            //mock HttpCurl
            $callback_handler = function($cfg) {
                
                return ;
                
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            
            $signature = Signature::single(null,$client);
            
            $s3client = new S3Client($handler);
            
            $filepath = 'notexisting.file';
            
            $s3client->uploadFile($signature, $filepath);
        
        }
        
        public function testS3Client_uploadFile_multipart_emptyfile() {
            
            //mock HttpCurl
            $callback_handler = function($cfg) {

                $this->assertArrayHasKey('chunk', $cfg['data']);
                
                $regex_key = '/^.+\.\d{1,}$/';
                $this->assertRegExp($regex_key, $cfg['data']['key']);
                
                $this->assertEmpty($cfg['data']['file']);
                
                return self::$httpCode201;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $filepath = 'movie.mp4';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            \touch($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $param = array('filename' => 'FileName',
                           'filesize' => 10024);
            
            $signature = Signature::multipart($param, $client);
            
            $s3client = new S3Client($handler);
            
            $s3client->uploadFile($signature, $filepath);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\S3uploadEx
         */
        public function testS3Client_uploadFile_Ex2() {
            
            //mock HttpCurl
            $callback_handler = function($cfg) {
                
                return self::$httpCode404;
                
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            
            $signature = Signature::single(null,$client);
            
            $s3client = new S3Client($handler);
            
            $filepath = 'movie.mp4';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            \touch($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $s3client->uploadFile($signature, $filepath);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
        }
        
        
        /**
         * @expectedException         VzaarApi\Exceptions\S3uploadEx
         */
        public function testS3Client_uploadFile_Ex3() {
            
            //mock HttpCurl
            $callback_handler = function($cfg) {
                
                return self::$httpCode404;
                
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            //mock Client
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                if($recordRequest['endpoint'] == '/signature/multipart')
                    return \json_decode(self::$multipart);
                
                if($recordRequest['endpoint'] == '/videos')
                    return \json_decode(self::$lookup);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            
            $param = array('filename' => 'FileName',
                           'filesize' => 10024);
            
            $signature = Signature::multipart($param, $client);
            
            $s3client = new S3Client($handler);
            
            $filepath = 'movie.mp4';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            \touch($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $s3client->uploadFile($signature, $filepath);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
        }
        
        public static function setUpBeforeClass()
        {
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 7574853,
                    "title": "multipart",
                    "user_id": 42,
                    "account_id": 1,
                    "description": null,
                    "duration": 66.7,
                    "created_at": "2016-11-11T11:36:26.000Z",
                    "updated_at": "2016-11-11T11:37:36.000Z",
                    "private": false,
                    "seo_url": "http://example.com/video.mp4",
                    "url": null,
                    "state": "ready",
                    "thumbnail_url": "https://view.vzaar.com/7574853/thumb",
                    "embed_code": "<iframe id=\"vzvd-7574853\" name=\"vzvd-7574853\" title=\"video player\" class=\"video-player\" type=\"text/html\" width=\"448\" height=\"278\" frameborder=\"0\" allowfullscreen allowTransparency=\"true\" mozallowfullscreen webkitAllowFullScreen src=\"//view.vzaar.com/7574853/player\"></iframe>",
                    "renditions": [
                    {
                        "id": 66,
                        "width": 416,
                        "height": 258,
                        "bitrate": 200,
                        "framerate": "12.0",
                        "status": "finished",
                        "size_in_bytes": 12345
                    }
                    ],
                    "legacy_renditions": [
                    {
                        "id": 10567122,
                        "type": "standard",
                        "width": 448,
                        "height": 278,
                        "bitrate": 512,
                        "status": "Finished"
                    }
                    ]
                }
            }
EOD;
            
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
            
            /*
             * fixture HTTP 201
             */
            $status = 'HTTP/1.1 201 Created'."\r\n";
            
            self::$httpCode201 = array('httpCode' => 201,
                                        'httpResponse' => $status);
            
            /*
             * fixture HTTP 201
             */
            self::$httpCode404 = array('httpCode' => 404,
                                       'httpResponse' => 'NOT FOUND');
            
    }
        
}
?>
