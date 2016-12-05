<?php
    require 'autoload.php';

    //
    // Create Signature: multipart
    //
    try {

      $multi = array();
      $filepath = './videos/medium.mp4';
      $multi['filename'] = basename($filepath);
      $multi['filesize'] = filesize($filepath);
      $sigMulti = VzaarApi\Signature::multipart($multi);
      echo PHP_EOL. 'Create Signature (multipart) - guid: '. $sigMulti->guid .' - parts: '. $sigMulti->parts .' - part_size_in_bytes: '. $sigMulti->part_size_in_bytes;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create Signature: single-part
    //
    try {

      $single = array();
      $sigSingle = VzaarApi\Signature::single($single);
      echo PHP_EOL. 'Create Signature (single-part) - guid: '. $sigSingle->guid;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }

    echo PHP_EOL;

?>

