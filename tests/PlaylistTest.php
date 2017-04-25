<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Playlist;
    
    class PlaylistTest extends VzaarTest
    {
        protected static $lookup;
        
        public function testPlaylist_New()
        {
            
            $playlist = new Playlist();
            
            $class = new \ReflectionClass($playlist);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/feeds/playlists',$endpoint->getValue());
            $this->assertInstanceOf(Client::class,$playlist->getClient());
            
        }
        
        public function testPlaylist_Parameter()
        {
            
            $client = new Client();
            $playlist = new Playlist($client);
            
            $class = new \ReflectionClass($playlist);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/feeds/playlists',$endpoint->getValue());
            $this->assertInstanceOf(Client::class,$playlist->getClient());
            
        }
        
        
        public function testPlaylist_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                $this->assertArrayHasKey('title',$recordRequest['recordData']);
                $this->assertArrayHasKey('category_id',$recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = array('title' => "Mocked Playlist",
                            'category_id' => 42);
            
            $playlist = Playlist::create($params,$client);
            
            $this->assertInstanceOf(Playlist::class,$playlist);
            $this->assertNotNull($playlist->id);
            
            
        }
        
        public function testPlaylist_find()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlist_id = 1;
            $playlist = Playlist::find($playlist_id,$client);
            
            $this->assertInstanceOf(Playlist::class,$playlist);
            $this->assertNotNull($playlist->id);
            
        }
        
        public function testPlaylist_save()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                $this->assertArrayHasKey('private',$recordRequest['recordData']);
                
                $result = \json_decode(self::$lookup);
                $result->data->private = $recordRequest['recordData']['private'];
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlist = new Playlist($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($playlist,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($playlist ,$jsondata);
            
            $old_value = $playlist->private;
            $playlist->private = !$old_value;
            $new_value = $playlist->private;
            
            $this->assertNotEquals($new_value, $old_value);
            
            $playlist->save();
            
            $saved_value = $playlist->private;
            
            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testPlaylist_save_params_Ex1() {
            
            $params = array('id' => 1,
                            'title' => "Mocked Playlist");
            
            $playlist = new Playlist();
            $id = isset($playlist->id) ? $playlist->id : null;
            
            $this->assertNull($id);
            
            $playlist->save($params);
            
        }
        
        
        public function testPlaylist_save_params() {
            
            $params = array('title' => "Mocked Playlist",
                            'autoplay' => true);
            
            $callback = function($recordRequest) {
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                $result = \json_decode(self::$lookup);
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlist = new Playlist($client);
            $playlist->id = 1;
            
            $playlist->save($params);
            
        }
        
        public function testPlaylist_delete()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('DELETE',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return true;
            };
            
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlist = new Playlist($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($playlist,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($playlist ,$jsondata);
            
            $playlist->delete();
            
            $id = isset($playlist->id) ? $playlist->id : null;
            
            $this->assertNull($id);
            
        }
        
        public function testPlaylistDelete_Params(){
            
            $callback = function($recordRequest) {
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                return true;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlist = new Playlist($client);
            $playlist->id = 1;
            
            $playlist->delete();
            
        }
        
        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 1,
                    "category_id": 42,
                    "title": "Mocked Playlist",
                    "sort_order": "desc",
                    "sort_by": "created_at",
                    "max_vids": 43,
                    "position": "right",
                    "private": false,
                    "dimensions": "768x340",
                    "autoplay": true,
                    "continuous_play": true,
                    "created_at": "2017-03-20T11:30:36.932Z",
                    "updated_at": "2017-03-20T11:30:36.932Z"
                }
            }
EOD;
            
        }
    }
?>
