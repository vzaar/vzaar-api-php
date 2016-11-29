<?php
    require 'autoload.php';
    
    try {
        
        /**
         * Signature Create Multipart Example
         *
         * create from filepath
         *
         */
        $filepath = '../movies/video_20MB.mp4';
        
        $sigMultiPath = VzaarApi\Signature::create($filepath);
        
        echo PHP_EOL. 'From filepath multi - guid: '. $sigMultiPath->guid .' - parts: '. $sigMultiPath->parts;
        
        /**
         * Signature Create Multipart Example
         *
         * create from user parameters
         *
         */
        $multi = array();
        $filepath = '../movies/video_20MB.mp4';
        
        $multi['filename'] = basename($filepath);
        $multi['filesize'] = filesize($filepath);
        $multi['uploader'] = 'MyUploader';
        
        $sigMulti = VzaarApi\Signature::multipart($multi);
        
        echo PHP_EOL. 'From params multi - guid: '. $sigMulti->guid .' - parts: '. $sigMulti->parts;
        
        /**
         * Signature Create Single Example
         *
         * create from filepath
         *
         */
        $filepath = '../movies/video_1MB.mp4';
        
        $sigSinglePath = VzaarApi\Signature::create($filepath);
        
        echo PHP_EOL. 'From filepath single - guid: '. $sigSinglePath->guid;
        
        /**
         * Signature Create Single Example
         *
         * create from user parameters
         *
         */
        $single = array();

        $single['uploader'] = 'MyUploader';
        
        $sigSingle = VzaarApi\Signature::single($single);
        
        echo PHP_EOL. 'From params single - guid: '. $sigSingle->guid;
        
        echo PHP_EOL;
        
    }catch(VzaarApi\VzaarException $ve){
        
        echo $ve->getMessage();
        
    }catch(VzaarApi\VzaarError $verr){
        
        echo $verr->getMessage();
        
    }
    ?>
