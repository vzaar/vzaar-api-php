<?php
    require 'autoload.php';

    //
    // Check rate limit
    //
    try {

      $encoding_preset = VzaarApi\Preset::find($encoding_preset_id);
      echo PHP_EOL. 'Rate Limit    : ' .$encoding_preset->checkRateLimit();
      echo PHP_EOL. 'Rate Remaining: ' .$encoding_preset->checkRateRemaining();
      echo PHP_EOL. 'Rate Reset    : ' .$encoding_preset->checkRateReset();
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }

    echo PHP_EOL;

?>
