<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Subtitle;

    class SubtitleTest extends VzaarTest
    {
        protected static $lookup;

        public function testSubtitle_New()
        {
            
            $subtitle = new Subtitle();
            
            $class = new \ReflectionClass($subtitle);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/videos',$endpoint->getValue());
            $this->assertInstanceOf(Client::class,$subtitle->getClient());
            
        }

        public function testPlaylist_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                $this->assertArrayHasKey('code',$recordRequest['recordData']);
                $this->assertArrayHasKey('content',$recordRequest['recordData']);

                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = [
                'code' => 'en',
                'content' => "1\n00:00:00,498 --> 00:00:02,827\nMy Subtitles",
            ];
            
            $subtitle = Subtitle::create(0,$params,$client);
            
            $this->assertInstanceOf(Subtitle::class,$subtitle);
            $this->assertNotNull($subtitle->id);
            
            
        }

        public function testSubtitle_save()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertArrayHasKey('code',$recordRequest['recordData']);
                
                $result = \json_decode(self::$lookup);
                $result->data->code = $recordRequest['recordData']['code'];
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $subtitle = new Subtitle($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($subtitle,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($subtitle ,$jsondata);
            
            $old_value = $subtitle->code;
            $subtitle->code = !$old_value;
            $new_value = $subtitle->code;
            
            $this->assertNotEquals($new_value, $old_value);
            
            $subtitle->save(123);
            
            $saved_value = $subtitle->code;
            
            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);
            
        }

        public function testSubtitle_delete()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('DELETE',$recordRequest['method']);
                $this->assertEquals('/videos', $recordRequest['endpoint']);
                
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return true;
            };
            
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $subtitle = new Subtitle($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($subtitle,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($subtitle ,$jsondata);
            
            $subtitle->delete(123);
            
            $id = isset($subtitle->id) ? $subtitle->id : null;
            
            $this->assertNull($id);
            
        }
        

        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 123,
                    "code": "en",
                    "title": "english-subtitles.srt",
                    "language": "English",
                    "created_at": "2018-02-07T15:49:05Z",
                    "updated_at": "2018-02-07T15:49:05Z",
                    "url": "https://view.vzaar.com/subtitles/123"
                }
            }
EOD;
            
        }
    }