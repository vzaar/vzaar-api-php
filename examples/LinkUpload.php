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
        $params['uploader'] = 'MyLinkUploader';
        
        $link = VzaarApi\LinkUpload::create($params);
        
        echo PHP_EOL.'Link Upload: id ' . $link->id .' - '. $link->title;
        
        
        echo PHP_EOL;
        
    }catch(VzaarApi\VzaarException $ve){
        
        echo $ve->getMessage();
        
    }catch(VzaarApi\VzaarError $verr){
        
        echo $verr->getMessage();
        
    }
?>
