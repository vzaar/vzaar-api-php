<?php
    require 'autoload.php';
    
    try {
        
        /**
         * LinkUpload Example
         *
         * create from user parameters
         *
         */
        $params = array();
        $params['url'] = "http://video.blendertestbuilds.de/download.blender.org/peach/trailer_480p.mov";
        $params['title'] = 'Link Upload Test';
        
        $link = VzaarApi\LinkUpload::create($params);
        
        echo PHP_EOL.'Link Upload: id ' . $link->id .' - '. $link->title;
        
        
        echo PHP_EOL;
        
    }catch(VzaarApi\Exceptions\VzaarException $ve){
        
        echo $ve->getMessage();
        
    }catch(VzaarApi\Exceptions\VzaarError $verr){
        
        echo $verr->getMessage();
        
    }
?>
