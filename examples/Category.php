<?php
    require 'autoload.php';
    
    try {
        
        /**
         * Category Lookup Example
         *
         */
        
        //no categories defined, Exception is thrown due to 404
        //define category and uncomment the below line, change category ID
        
        //$category = Vzaar\Category::find(1);
        
        /**
         * Category Subtree Example
         *
         * instance level method call
         * this returns subtree List of current category in the object
         */
        
        //no categories defined, Exception is thrown due to 404
        //after category is defined and found with above ::find()
        //uncomment below line
        
        //$subInstance = $category->subtree();
        
        /**
         * Category Subtree Example
         *
         * class level (static) method call
         * the method returns subtree list
         */
        
        //no categories defined, exception is thrown due to 404
        //uncomment when category defined
        
        //$subStatic = Vzaar\CategoriesList::subtree(1);
        
        /**
         * Category List Example
         *
         * Iterate over recipes
         *
         */
        
        foreach(Vzaar\CategoriesList::each_item() as $category) {
        
            if(isset($category->id) & isset($category->name)){
            
                echo PHP_EOL.'List Category: id: ' .$category->id .''. $category->name;
            }
        
        }
        
        echo PHP_EOL;
        
    
    }catch(Vzaar\VzaarException $ve){
    
        echo $ve->getMessage();
        
    }catch(Vzaar\VzaarError $verr){
    
        echo $verr->getMessage();
        
    }
?>
