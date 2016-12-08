<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\PresetsList;
    use VzaarApi\Preset;
    use VzaarApi\Client;
    
    class PresetsListTest extends VzaarTest {
        
        public function testRecipesList_New () {
            
            $presets = new PresetsList();
            
            $class = new \ReflectionClass($presets);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            
            $this->assertEquals('/encoding_presets', $endpoint->getValue());
            $this->assertEquals(Preset::class, $recordClass->getValue());
            
            $this->assertInstanceOf(PresetsList::class, $presets);
            
        }
    }
?>
