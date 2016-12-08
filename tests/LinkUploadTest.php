<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\LinkUpload;
    use VzaarApi\Video;
    
    class LinkUploadTest extends VzaarTest
    {
        protected static $lookup;
        
        public function testLinkUpload_New()
        {
            
            $link = new LinkUpload();
            
            $class = new \ReflectionClass($link);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/link_uploads',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $link->getClient());
            
        }
        
        public function testLinkUpload_Parameter()
        {
            
            $client = new Client();
            $link = new LinkUpload($client);
            
            $class = new \ReflectionClass($link);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/link_uploads',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $link->getClient());
            
        }
        
        public function testLinkUpload_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/link_uploads', $recordRequest['endpoint']);
                
                $this->assertEmpty($recordRequest['recordPath']);
                
                $this->assertArrayHasKey('uploader', $recordRequest['recordData']);
                $this->assertArrayHasKey('url', $recordRequest['recordData']);
                $this->assertArrayHasKey('title', $recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $param = array('url' => 'https://example.com/video.mp4',
                           'title' => 'Linked video');
            
            $link = LinkUpload::create($param, $client);
            
            $this->assertInstanceOf(Video::class, $link);
            $this->assertNotNull($link->id);
            
        }
        
        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 1,
                    "title": "My videos",
                    "user_id": 42,
                    "account_id": 1,
                    "description": null,
                    "created_at": "2016-08-22T08:51:24.000Z",
                    "updated_at": "2016-08-22T08:53:16.000Z",
                    "private": false,
                    "seo_url": null,
                    "url": null,
                    "thumbnail_url": "https://view.vzaar.localhost/1/thumb",
                    "renditions": []
                }
            }
EOD;
            
        }
    }
?>
