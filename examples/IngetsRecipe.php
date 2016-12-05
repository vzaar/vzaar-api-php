<?php
    require 'autoload.php';

    //
    // Lookup IngestRecipe
    //
    try {

      $ingest_recipe = VzaarApi\Recipe::find($default_ingest_recipe);
      echo PHP_EOL.'Lookup IngestRecipe: id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List IngestRecipe: Iterate over presets
    //
    try {

      foreach(VzaarApi\RecipesList::each_item() as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'List IngestRecipe (iterate): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create/Update/Delete IngestRecipe
    //
    try {

      $params = array();
      $params['name'] = 'new SDK recipe';
      $params['description'] = 'created by the SDK tests';
      $params['default'] = true;
      $params['encoding_preset_ids'] = [2, 3, 4];

      // Create new default recipe
      $recipe = VzaarApi\Recipe::create($params);
      echo PHP_EOL.'Create id: ' . $recipe->id  .' - '. $recipe->name .' - '. $recipe->default;

      // ensure previous default has changed
      $default = VzaarApi\Recipe::find($default_ingest_recipe);
      echo PHP_EOL.'Default recipe: ' . $default->id  .' - '. $default->default;

      // Update recipe
      $recipe->name = 'updated by PHP';
      $recipe->encoding_preset_ids = [4, 5, 6];

      if($recipe->edited())
        $recipe->save();

      $recipe = VzaarApi\Recipe::find($recipe->id);
      echo PHP_EOL.'Updated recipe: ' . $recipe->id  .' - '. $recipe->name;

      // restore previous default
      $default->default = true;
      $default->save();
      $default = VzaarApi\Recipe::find($default_ingest_recipe);
      echo PHP_EOL.'Default recipe: ' . $default->id  .' - '. $default->default;

      // Delete recipe
      $recipe->delete();

      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Paginate IngestRecipe
    //
    try {

      $paramsList = array('per_page' => '2');

      //this methods gets initial page
      $pager = VzaarApi\RecipesList::paginate($paramsList);

      foreach($pager as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'Paginate IngestRecipe (first): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
        }
      }
      echo PHP_EOL;

      $pager->nextPage();
      foreach($pager as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'Paginate IngestRecipe (next): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
        }
      }
      echo PHP_EOL;

      $pager->lastPage();
      foreach($pager as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'Paginate IngestRecipe (last): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
        }
      }
      echo PHP_EOL;

      $pager->previousPage();
      foreach($pager as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'Paginate IngestRecipe (previous): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
        }
      }
      echo PHP_EOL;

      $pager->firstPage();
      foreach($pager as $ingest_recipe) {
        if(isset($ingest_recipe->id) & isset($ingest_recipe->name)){
          echo PHP_EOL.'Paginate IngestRecipe (first): id: ' .$ingest_recipe->id .' '. $ingest_recipe->name;
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
