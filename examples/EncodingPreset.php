<?php
    require 'autoload.php';

    //
    // Lookup EncodingPreset
    //
    try {

      $encoding_preset = VzaarApi\Preset::find($encoding_preset_id);
      echo PHP_EOL.'Lookup EncodingPreset: id: ' .$encoding_preset->id .' '. $encoding_preset->name;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List EncodingPreset: Iterate over presets
    //
    try {

      foreach(VzaarApi\PresetsList::each_item() as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'List EncodingPreset (iterate): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Paginate EncodingPreset
    //
    try {

      $paramsList = array('per_page' => '2');

      //this methods gets initial page
      $pager = VzaarApi\PresetsList::paginate($paramsList);

      foreach($pager as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'Paginate EncodingPreset (first): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
        }
      }
      echo PHP_EOL;

      $pager->nextPage();
      foreach($pager as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'Paginate EncodingPreset (next): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
        }
      }
      echo PHP_EOL;

      $pager->lastPage();
      foreach($pager as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'Paginate EncodingPreset (last): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
        }
      }
      echo PHP_EOL;

      $pager->previousPage();
      foreach($pager as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'Paginate EncodingPreset (previous): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
        }
      }
      echo PHP_EOL;

      $pager->firstPage();
      foreach($pager as $encoding_preset) {
        if(isset($encoding_preset->id) & isset($encoding_preset->name)){
          echo PHP_EOL.'Paginate EncodingPreset (first): id: ' .$encoding_preset->id .' '. $encoding_preset->name;
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
