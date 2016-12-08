<?php
    namespace VzaarApi\Tests;
    
    use VzaarApi\Tests\VzaarTest;
    use VzaarApi\VideosList;
    use VzaarApi\Video;
    use VzaarApi\Client;
    
    class VideosListTest extends VzaarTest {
        
        public function testVideosList_New () {
            
            $videos = new VideosList();
            
            $class = new \ReflectionClass($videos);
            $endpoint = $class->getProperty('endpoint');
            $endpoint->setAccessible(true);
            $recordClass = $class->getProperty('recordClass');
            $recordClass->setAccessible(true);
            
            $this->assertEquals('/videos', $endpoint->getValue());
            $this->assertEquals(Video::class, $recordClass->getValue());
            
            $this->assertInstanceOf(VideosList::class, $videos);
            
        }
    }
?>
