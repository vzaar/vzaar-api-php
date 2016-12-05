<?php
    require 'autoload.php';

    //
    // Create LinkUpload
    //
    try {

      $params = array();
      $params['url'] = "https://www.dropbox.com/s/zu1n51dm9sabogq/dropbox-video.mp4?dl=0";
      $params['title'] = 'Link Upload Test';
      $video = VzaarApi\LinkUpload::create($params);
      echo PHP_EOL.'Link Upload: id ' . $video->id .' - '. $video->title;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }

    echo PHP_EOL;

?>
