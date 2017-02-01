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


    //
    // Paginate Category
    //
    try {

      $paramsList = array('per_page' => '2');

      //this methods gets initial page
      $pager = VzaarApi\CategoriesList::paginate($paramsList);

      foreach($pager as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'Paginate Category (first): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

      $pager->nextPage();
      foreach($pager as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'Paginate Category (next): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

      $pager->lastPage();
      foreach($pager as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'Paginate Category (last): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

      $pager->previousPage();
      foreach($pager as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'Paginate Category (previous): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

      $pager->firstPage();
      foreach($pager as $category) {
        if(isset($category->id) & isset($category->name)){
          echo PHP_EOL.'Paginate Category (first): id: ' .$category->id .' '. $category->name;
        }
      }
      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }


    //
    // Create / Update / Delete
    //
    try {

      // create

      $params = array('name' => 'category from php sdk examples');

      $cat = VzaarApi\Category::create($params);
      echo PHP_EOL.'Created Category: ' .$cat->id .' '. $cat->name;

      $params['parent_id'] = $cat->id;
      $subcat = VzaarApi\Category::create($params);
      echo PHP_EOL.'Created Category: ' .$subcat->id .' '. $subcat->name;

      $params['parent_id'] = $subcat->id;
      $subsubcat = VzaarApi\Category::create($params);
      echo PHP_EOL.'Created Category: ' .$subsubcat->id .' '. $subsubcat->name;

      echo PHP_EOL;

      // update

      echo PHP_EOL.'Updating Category: ' .$subcat->id .' ('. $subcat->name .') moving from depth ' .$subcat->depth .' to root';
      $subcat->name = 'updated name from php sdk';
      $subcat->move_to_root = true;
      $subcat->save();

      echo PHP_EOL.'Updated Category: ' .$subcat->id .' ('. $subcat->name .') depth is now ' .$subcat->depth;

      echo PHP_EOL;

      echo PHP_EOL.'Moving it back again...';
      $subcat->name = 'updated name again from php sdk';
      $subcat->parent_id = $cat->id;
      $subcat->save();
      echo PHP_EOL.'Updated Category: ' .$subcat->id .' ('. $subcat->name .') depth is now ' .$subcat->depth;

      echo PHP_EOL;

      echo PHP_EOL.'Deleting category: ' .$cat->id .' and its subcategories';
      $cat->delete();

      echo PHP_EOL;

    } catch(VzaarApi\Exceptions\VzaarException $ve) {

      echo $ve->getMessage();

    } catch(VzaarApi\Exceptions\VzaarError $verr) {

      echo $verr->getMessage();

    }



    echo PHP_EOL;

?>
