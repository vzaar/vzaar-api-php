<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\RecipesList;
    use VzaarApi\Recipe;
    use VzaarApi\Client;
    
    class RecipesListTest extends VzaarTest {
        
        public static $list;
    
        public function testRecipesList_New () {
            
            $recipes = new RecipesList();
            
            $class = new \ReflectionClass($recipes);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            
            $this->assertEquals('/ingest_recipes', $endpoint->getValue());
            $this->assertEquals(Recipe::class, $recordClass->getValue());
            
            
            $this->assertInstanceOf(RecipesList::class, $recipes);
            
        }
        
        public function testRecipesList_paginate() {
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);
                
                return \json_decode(self::$list);
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $recipes = RecipesList::paginate(null,$client);
            
            $class = new \ReflectionClass($recipes);
            $recordData = $class->getProperty('recordData');
            $recordData->setAccessible(true);
            
            $this->assertInstanceOf(Recipe::class, $recordData->getValue($recipes)->data[0]);
            $this->assertEquals(1, \count($recipes));
            
        }
        
        public function testRecipesList_each_item() {
            
            
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);
                
                $result = \json_decode(self::$list);
                //reset 'next' link
                $result->meta->links->next = null;
                
                return $result;
                
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            foreach(RecipesList::each_item(null, $client) as $recipe) {
                
                $this->assertInstanceOf(Recipe::class, $recipe);
                
            }
            
        }
        
        public static function setUpBeforeClass()
        {
            self::$list=<<<EOD
            {
                "data": [
                {
                    "id": 1,
                    "name": "My recipe",
                    "recipe_type": "new_video",
                    "description": "Test",
                    "account_id": 79357,
                    "user_id": 79357,
                    "default": true,
                    "multipass": false,
                    "frame_grab_time": "3.5",
                    "generate_animated_thumb": true,
                    "generate_sprite": true,
                    "use_watermark": true,
                    "send_to_youtube": false,
                    "encoding_presets": [
                    {
                        "id": 2,
                        "name": "Do Not Encode",
                        "description": "",
                        "output_format": "mp4",
                        "bitrate_kbps": null,
                        "max_bitrate_kbps": null,
                        "long_dimension": null,
                        "video_codec": null,
                        "profile": "MP3",
                        "frame_rate_upper_threshold": null,
                        "audio_bitrate_kbps": null,
                        "audio_channels": null,
                        "audio_sample_rate": null,
                        "created_at": "2016-11-09T11:01:38.000Z",
                        "updated_at": "2016-11-09T11:01:38.000Z"
                    }
                    ],
                    "created_at": "2016-11-09T11:01:38.000Z",
                    "updated_at": "2016-11-25T13:30:41.000Z"
                }
                ],
                "meta": {
                    "total_count": 1,
                    "links": {
                        "first": "http://api.vzaar.com/api/v2/ingest_recipes?page=1&per_page=1",
                        "next": "http://api.vzaar.com/api/v2/ingest_recipes?page=2&per_page=1",
                        "previous": null,
                        "last": "http://api.vzaar.com/api/v2/ingest_recipes?page=2&per_page=1"
                    }
                }
            }
EOD;
        }
    
    }
?>
