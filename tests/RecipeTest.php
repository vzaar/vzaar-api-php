<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Recipe;
    
    class RecipeTest extends VzaarTest
    {
        protected static $lookup;
        
        public function testRecipe_New()
        {
            
            $recipe = new Recipe();
            
            $class = new \ReflectionClass($recipe);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/ingest_recipes',$endpoint->getValue());
            $this->assertInstanceOf(Client::class,$recipe->getClient());
            
        }
        
        public function testRecipe_Parameter()
        {
            
            $client = new Client();
            $recipe = new Recipe($client);
            
            $class = new \ReflectionClass($recipe);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/ingest_recipes',$endpoint->getValue());
            $this->assertInstanceOf(Client::class,$recipe->getClient());
            
        }
        
        
        public function testRecipe_create()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('POST',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);
                
                $this->assertArrayNotHasKey('id',$recordRequest['recordData']);
                $this->assertArrayHasKey('name',$recordRequest['recordData']);
                $this->assertArrayHasKey('encoding_preset_ids',$recordRequest['recordData']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $params = array('name' => "Mocked Recipe",
                            'encoding_preset_ids' => [2, 3]);

            $recipe = Recipe::create($params,$client);
            
            $this->assertInstanceOf(Recipe::class,$recipe);
            $this->assertNotNull($recipe->id);
            
            
        }
        
        public function testRecipe_find()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);
                
                $this->assertEquals(1, $recordRequest['recordPath']);

                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $recipe_id=1;
            $recipe = Recipe::find($recipe_id,$client);
            
            $this->assertInstanceOf(Recipe::class,$recipe);
            $this->assertNotNull($recipe->id);
            
        }
        
        public function testRecipe_save()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('PATCH',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);

                $this->assertArrayHasKey('multipass',$recordRequest['recordData']);
                
                $result = \json_decode(self::$lookup);
                $result->data->multipass = $recordRequest['recordData']['multipass'];
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
        
            $recipe = new Recipe($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($recipe,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($recipe ,$jsondata);
            
            $old_value = $recipe->multipass;
            $recipe->multipass = !$old_value;
            $new_value = $recipe->multipass;
            
            $this->assertNotEquals($new_value, $old_value);
            
            $recipe->save();
           
            $saved_value = $recipe->multipass;
            
            $this->assertNotEquals($saved_value, $old_value);
            $this->assertEquals($new_value, $saved_value);
            
        }
        
        /**
         * @expectedException         VzaarApi\Exceptions\RecordEx
         * @expectedExceptionMessage  Record corrupted, missing id
         */
        public function testRecipe_save_params_Ex1() {
            
            $params = array('id' => 1,
                            'name' => "Mocked Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $recipe = new Recipe();
            $id = isset($recipe->id) ? $recipe->id : null;
            
            $this->assertNull($id);
            
            $recipe->save($params);
            
        }
        

        public function testRecipe_save_params() {
            
            $params = array('name' => "Mocked Recipe",
                            'encoding_preset_ids' => [2, 3]);
            
            $callback = function($recordRequest) {
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                $result = \json_decode(self::$lookup);
                
                return $result;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $recipe = new Recipe($client);
            $recipe->id = 1;
            
            $recipe->save($params);
            
        }
        
        public function testRecipe_delete()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('DELETE',$recordRequest['method']);
                $this->assertEquals('/ingest_recipes', $recordRequest['endpoint']);
                
                $this->assertNotEmpty($recordRequest['recordPath']);
                $this->assertEmpty($recordRequest['recordQuery']);
                $this->assertEmpty($recordRequest['recordData']);
                
                return true;
            };
            
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $recipe = new Recipe($client);
            
            $jsondata = \json_decode(self::$lookup);
            
            $updateRecord = new \ReflectionMethod($recipe,'updateRecord');
            $updateRecord->setAccessible(true);
            $updateRecord->invoke($recipe ,$jsondata);
            
            $recipe->delete();
            
            $id = isset($recipe->id) ? $recipe->id : null;
            
            $this->assertNull($id);
            
        }
        
        public function testRecipeDelete_Params(){
            
            $callback = function($recordRequest) {
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                return true;
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $recipe = new Recipe($client);
            $recipe->id = 1;
            
            $recipe->delete();
            
        }
        
        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 1,
                    "name": "Mocked Recipe",
                    "recipe_type": "new_video",
                    "description": null,
                    "account_id": 1,
                    "user_id": 42,
                    "default": true,
                    "multipass": false,
                    "frame_grab_time": "3.5",
                    "generate_animated_thumb": true,
                    "generate_sprite": true,
                    "use_watermark": true,
                    "send_to_youtube": false,
                    "encoding_presets": [
                    {
                        "id": 1,
                        "name": "Preset 1",
                        "width": 416,
                        "bitrate": 200,
                        "frame_rate": "12.0",
                        "created_at": "2016-10-26T11:00:54.000Z",
                        "updated_at": "2016-10-26T11:00:54.000Z"
                    },
                    {
                        "id": 2,
                        "name": "Preset 2",
                        "width": 480,
                        "bitrate": 400,
                        "frame_rate": "29.97",
                        "created_at": "2016-10-26T11:00:54.000Z",
                        "updated_at": "2016-10-26T11:00:54.000Z"
                    }
                    ],
                    "created_at": "2016-10-26T11:00:55.000Z",
                    "updated_at": "2016-10-26T11:00:55.000Z"
                }
            }
EOD;

        }
    }
?>
