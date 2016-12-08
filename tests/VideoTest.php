<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Resources\S3Client;
    use VzaarApi\Resources\HttpCurl;
    use VzaarApi\Client;
    use VzaarApi\Video;
    
    class VideoTest extends VzaarTest
    {
        public static $lookup;
        public static $single;
        
        public function testVideo_New()
        {
            
            $video = new Video();
            
            $class = new \ReflectionClass($video);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $s3client = $class->getProperty('s3client');
            $s3client->setAccessible(true);
            
            $this->assertEquals('/videos',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $video->getClient());
            $this->assertInstanceOf(S3Client::class, $s3client->getValue($video));
            
        }
        
        public function testVideo_Parameter()
        {
            
            $client = new Client();
            $video = new Video($client);
            
            $class = new \ReflectionClass($video);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/videos',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $video->getClient());
            
        }
        
        public function testVideo_find()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertEquals(7574853, $recordRequest['recordPath']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $video_id=7574853;
            $video = Video::find($video_id, $client);
            
            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);
            
        }
        
        public function testVideo_create_from_guid()
        {
        }
        
        public function testVideo_create_from_file()
        {
            $callback = function($recordRequest) {
                
                if($recordRequest['endpoint'] == '/signature/single')
                    return \json_decode(self::$single);
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('guid', $recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $callback_handler = function($recordRequest) {
                
                $result = array('httpCode' => 201);
                
                return $result;
            };
            
            $handler = $this->createMock(HttpCurl::class);
            $handler->method('httpRequest')
            ->will($this->returnCallback($callback_handler, $this->returnArgument(0)));
            
            $s3client = new S3Client($handler);
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            //create test file
            $filepath = 'movie.mp4';
            
            //cleanup before
            if(file_exists($filepath))
                unlink($filepath);
            
            file_put_contents($filepath,'filecontent');
            
            $param = array('filepath' => $filepath);
            
            $video = Video::create($param, $client, $s3client);
            
            //cleanup after
            if(file_exists($filepath))
                unlink($filepath);
            
            //clear file caches
            clearstatcache(true, $filepath);
            
            $this->assertInstanceOf(Video::class,$video);
            $this->assertNotNull($video->id);
            
        }
        
        public function testVideo_create_from_url()
        {
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
