<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\PlaylistsList;
    use VzaarApi\Playlist;
    use VzaarApi\Client;
    
    class PlaylistsListTest extends VzaarTest {
        
        public static $list;
        
        public function testPlaylistsList_New () {
            
            $playlists = new PlaylistsList();
            
            $class = new \ReflectionClass($playlists);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            
            $this->assertEquals('/feeds/playlists', $endpoint->getValue());
            $this->assertEquals(Playlist::class, $recordClass->getValue());
            
            
            $this->assertInstanceOf(PlaylistsList::class, $playlists);
            
        }
        
        public function testPlaylistsList_paginate() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                return \json_decode(self::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $playlists = PlaylistsList::paginate(null,$client);
            
            $class = new \ReflectionClass($playlists);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertInstanceOf(Playlist::class, $recordData->getValue($playlists)->data[0]);
            $this->assertEquals(1, \count($playlists));
            
        }
        
        public function testPlaylistsList_each_item() {
            
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/feeds/playlists', $recordRequest['endpoint']);
                
                $result = \json_decode(self::$list);
                //reset 'next' link
                $result->meta->links->next = null;
                
                return $result;
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            foreach(PlaylistsList::each_item(null, $client) as $playlist) {
                
                $this->assertInstanceOf(Playlist::class, $playlist);
                
            }
            
        }
        
        public static function setUpBeforeClass()
        {
            self::$list= <<<EOD
                {
                    "data": [
                    {
                        "id": 1,
                        "title": "drj-playlist-cat-user-test",
                        "sort_order": "asc",
                        "sort_by": "created_at",
                        "max_vids": 30,
                        "position": "left",
                        "private": false,
                        "dimensions": "auto",
                        "autoplay": false,
                        "continuous_play": false,
                        "category_id": 42,
                        "created_at": "2016-11-09T11:01:38.000Z",
                        "updated_at": "2016-11-25T13:30:41.000Z"
                    }
                    ],
                    "meta": {
                        "total_count": 1,
                        "links": {
                            "first": "http://api.vzaar.com/api/v2/feeds/playlists?page=1&per_page=1",
                            "next": "http://api.vzaar.com/api/v2/feeds/playlists?page=2&per_page=1",
                            "previous": null,
                            "last": "http://api.vzaar.com/api/v2/feeds/playlists?page=2&per_page=1"
                        }
                    }
                }
EOD;
        }
        
    }
?>
