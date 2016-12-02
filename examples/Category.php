<?php
    require 'autoload.php';

    //
    // Lookup Category
    //
    try {

      $category = VzaarApi\Category::find($category_id);
      echo PHP_EOL.'Lookup Category: id: ' .$category->id .' '. $category->name;
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List Category: Iterate over categories
    //
    try {

      foreach(VzaarApi\CategoriesList::each_item() as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'List Category (iterate): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List Category Subtree (instance)
    //
    try {

      $top_category = VzaarApi\Category::find($category_id);
      foreach($top_category->subtree() as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'List Category Subtree (instance): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // List Category Subtree (static)
    //
    try {

      foreach(VzaarApi\CategoriesList::subtree($category_id) as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'List Category Subtree (static): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }

?>
