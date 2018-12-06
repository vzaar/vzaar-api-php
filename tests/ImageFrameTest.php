<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\ImageFrame;

    class ImageFrameTest extends VzaarTest
    {

        protected static $lookup;

        
        public function testImageFrame_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = array('title' => "Mocked Image",
                            'filepath' => 'abc.png');
            
            $image = ImageFrame::create(123,$params,$client);
            
            $this->assertInstanceOf(ImageFrame::class,$image);
            $this->assertNotNull($image->id);
            
        }

        public function testImageFrame_set()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = array('time' => 2.5);
            
            $image = ImageFrame::set(123,$params,$client);
            
            $this->assertInstanceOf(ImageFrame::class,$image);
            $this->assertNotNull($image->id);
            
        }

        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 123,
                    "title": "My Video",
                    "description": "Video Description goes here",
                    "state": "ready",
                    "private": false,
                    "user_id": 42,
                    "user_login": "my-login",
                    "account_id": 101,
                    "account_name": "my-account",
                    "duration": 1050,
                    "created_at": "2018-01-17T15:48:44.000Z",
                    "updated_at": "2018-01-17T18:02:06.000Z",
                    "url": "https://vzaar.com/videos/123",
                    "seo_url": null,
                    "asset_url": "https://view.vzaar.com/123/video",
                    "poster_url": "https://view.vzaar.com/123/image",
                    "thumbnail_url": "https://view.vzaar.com/123/thumb",
                    "embed_code": "<iframe id=\"vzvd-123\" name=\"vzvd-123\" title=\"video player\" class=\"video-player\" type=\"text/html\" width=\"448\" height=\"278\" frameborder=\"0\" allowfullscreen allowTransparency=\"true\" mozallowfullscreen webkitAllowFullScreen src=\"//view.vzaar.com/123/player\"></iframe>",
                    "categories": [],
                    "adverts": [],
                    "renditions": []
                }
            }
EOD;
            
        }

    }