<?php
    require 'autoload.php';

    //
    // Create Video (single-part upload)
    //
    try {

      $paramsFile = array('filepath' => './videos/small.mp4');
      $paramsFile['title'] = 'Test Video 3.1MB';

      $videoFile = VzaarApi\Video::create($paramsFile);
      echo PHP_EOL.'Create (single-part upload): '. $videoFile->id .'-'. $videoFile->title;

      $lookupFile = VzaarApi\Video::find($videoFile->id);
      echo PHP_EOL.'Lookup: '. $lookupFile->id .'-'. $lookupFile->title;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create Video (multipart upload)
    //
    try {

      $paramsFile = array('filepath' => './videos/medium.mp4');
      $paramsFile['title'] = 'Test Video 7.2MB';

      $videoFile = VzaarApi\Video::create($paramsFile);
      echo PHP_EOL.'Create (multipart upload): '. $videoFile->id .'-'. $videoFile->title;

      $lookupFile = VzaarApi\Video::find($videoFile->id);
      echo PHP_EOL.'Lookup: '. $lookupFile->id .'-'. $lookupFile->title;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create Video (link upload)
    //
    try {

      $paramsUrl = array('url' => "https://www.dropbox.com/s/zu1n51dm9sabogq/dropbox-video.mp4?dl=0");
      $paramsFile['title'] = 'Test Video link upload';

      $videoFile = VzaarApi\Video::create($paramsFile);
      echo PHP_EOL.'Create (link upload): '. $videoFile->id .'-'. $videoFile->title;

      $lookupFile = VzaarApi\Video::find($videoFile->id);
      echo PHP_EOL.'Lookup: '. $lookupFile->id .'-'. $lookupFile->title;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create Video (manual upload, using signature)
    //
    try {

      $params = array();
      $filepath = './videos/small.mp4';
      $params['title'] = 'Test Upload 3.1MB';

      $signature = VzaarApi\Signature::create($filepath);

      $s3 = new VzaarApi\Resources\S3Client();
      $s3->uploadFile($signature,$filepath);

      $params['guid'] = $signature->guid;

      $videoGuid = VzaarApi\Video::create($params);

      echo PHP_EOL.'Guid Create: '. $videoGuid->id .'-'. $videoGuid->title;

      $lookupGuid = VzaarApi\Video::find($videoGuid->id);

      echo PHP_EOL.'Guid Lookup: '. $lookupGuid->id .'-'. $lookupGuid->title;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List Video: Iterate over videos
    //
    try {

      foreach(VzaarApi\VideosList::each_item() as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'List Video (iterate): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Paginate Video
    //
    try {

      $paramsList = array('per_page' => '2');

      //this methods gets initial page
      $pager = VzaarApi\VideosList::paginate($paramsList);

      foreach($pager as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'Paginate Video (first): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

      $pager->nextPage();
      foreach($pager as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'Paginate Video (next): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

      $pager->lastPage();
      foreach($pager as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'Paginate Video (last): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

      $pager->previousPage();
      foreach($pager as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'Paginate Video (previous): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

      $pager->firstPage();
      foreach($pager as $video) {
        if(isset($video->id) & isset($video->title)){
          echo PHP_EOL.'Paginate Video (first): id: ' .$video->id .' '. $video->title;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }

    echo PHP_EOL;

?>
