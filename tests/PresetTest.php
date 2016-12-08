<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\Exceptions\RecordEx;
    use VzaarApi\Client;
    use VzaarApi\Preset;
    
    class PresetTest extends VzaarTest
    {
        protected static $lookup;
        
        public function testPreset_New()
        {
            
            $preset = new Preset();
            
            $class = new \ReflectionClass($preset);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/encoding_presets',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $preset->getClient());
            
        }
        
        public function testPreset_Parameter()
        {
            
            $client = new Client();
            $preset = new Preset($client);
            
            $class = new \ReflectionClass($preset);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            
            $this->assertEquals('/encoding_presets',$endpoint->getValue());
            $this->assertInstanceOf(Client::class, $preset->getClient());
            
        }
        
        public function testPreset_find()
        {
            $callback = function($recordRequest) {
                
                $this->assertEquals('GET',$recordRequest['method']);
                $this->assertEquals('/encoding_presets', $recordRequest['endpoint']);
                
                $this->assertEquals(1, $recordRequest['recordPath']);
                
                return \json_decode(self::$lookup);
            };
            
            $client = $this->createMock(Client::class);
            $client->method('clientSend')
            ->will($this->returnCallback($callback,$this->returnArgument(0)));
            
            $preset_id=1;
            $preset = Preset::find($preset_id, $client);
            
            $this->assertInstanceOf(Preset::class,$preset);
            $this->assertNotNull($preset->id);
            
        }
        
        public static function setUpBeforeClass()
        {
            
            self::$lookup = <<<EOD
            {
                "data": {
                    "id": 3,
                    "name": "ULD",
                    "description": "Ultra Low Definition",
                    "output_format": "mp4",
                    "bitrate_kbps": 200,
                    "max_bitrate_kbps": 260,
                    "long_dimension": 416,
                    "video_codec": "libx264",
                    "profile": "main",
                    "frame_rate_upper_threshold": "12.0",
                    "audio_bitrate_kbps": 128,
                    "audio_channels": 2,
                    "audio_sample_rate": 44100,
                    "created_at": "2016-10-24T12:36:47.000Z",
                    "updated_at": "2016-10-24T12:36:47.000Z"
                }
            }
EOD;
            
        }
    }
    ?>
