<?php
    require 'autoload.php';
    
    try {
        
        /**
         * Video Create Example
         *
         * create from filepath
         *
         */
        
        $paramsFile = array('filepath' => 'movies/video_1MB.mp4');
        $paramsFile['title'] = 'Test Video 1MB';
        
        $videoFile = Vzaar\Video::create($paramsFile);
        
        echo PHP_EOL.'Create: '. $videoFile->id .'-'. $videoFile->title;
        
        $lookupFile = Vzaar\Video::find($videoFile->id);
        
        echo PHP_EOL.'Lookup: '. $lookupFile->id .'-'. $lookupFile->title;
        
        /**
         * Video Create Example
         *
         * create from url
         *
         */
        $paramsUrl = array('url' => "http://video.blendertestbuilds.de/download.blender.org/peach/trailer_480p.mov");
        $paramsUrl['title'] = 'Test Video URL';
        
        $videoUrl = Vzaar\Video::create($paramsUrl);
        
        echo PHP_EOL.'Url Create: '. $videoUrl->id .'-'. $videoUrl->title;
        
        $lookupUrl = Vzaar\Video::find($videoUrl->id);
        
        echo PHP_EOL.'Url Lookup: '. $lookupUrl->id .'-'. $lookupUrl->title;
        
        /**
         * Video Create Example
         *
         * create from guid
         *
         */
        $params = array();
        $filepath = 'movies/video_20MB.mp4';
        $params['title'] = 'Test Upload 20MB';
        
        $signature = Vzaar\Signature::create($filepath);
        
        $s3 = new Vzaar\S3Client();
        $s3->uploadFile($signature,$filepath);
        
        $params['guid'] = $signature->guid;
        
        $videoGuid = Vzaar\Video::create($params);
        
        echo PHP_EOL.'Guid Create: '. $videoGuid->id .'-'. $videoGuid->title;
        
        $lookupGuid = Vzaar\Video::find($videoGuid->id);
        
        echo PHP_EOL.'Guid Lookup: '. $lookupGuid->id .'-'. $lookupGuid->title;
        
        
        
        
        /**
         * Video List Example
         *
         * iterate over current/first page of results
         *
         */
        $paramsList = array('order' => 'asc',
                            'state' => 'ready');
        
        //this methods gets initial page
        $list = Vzaar\VideosList::paginate($paramsList);
        
        foreach($list as $video) {
            
            echo PHP_EOL.'Ready List: '. $video->title;
        }
        
        /**
         * Video List Example
         *
         * iterate over next page of results if not empty
         *
         */
        $list->nextPage();
        
        foreach($list as $video) {
            
            echo PHP_EOL.'Ready List: '. $video->id;
        }
        
        /**
         * Video List Example
         *
         * iterate the rest of the list
         *
         */
        while($list->nextPage()) {
            
            foreach($list as $video) {
                
                echo PHP_EOL.'Ready List: '. $video->id;
            }
        }
        
        /**
         * Video List Example
         *
         * iterate through all videos found with given query
         *
         */
        $paramsList['state'] = 'failed';
        
        foreach(Vzaar\VideosList::each_item($paramsList) as $video) {
            
            echo PHP_EOL.'Failed List: '. $video->title;
        }
        
        $paramsList['state'] = 'processing';
        
        foreach(Vzaar\VideosList::each_item($paramsList) as $video) {
            
            echo PHP_EOL.'Processing List: '. $video->title;
        }
        
        echo PHP_EOL;
    
    } catch(Vzaar\VzaarException $ve) {
        
        echo $ve->getMessage();
    
    } catch(Vzaar\VzaarError $verr) {
        
        echo $verr->getMessage();
    
    }
?>
