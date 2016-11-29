<?php
    require 'autoload.php';
    
    try {
        
        /**
         * Ingest Recipe Example
         *
         * Create/Lookup/Update/Delete
         *
         */
        
        $params = array();
        $params['name'] = "Example Recipe 123";
        
        //get prestes
        foreach(Vzaar\PresetsList::each_item() as $preset) {
            
            $params['encoding_preset_ids'][] = $preset->id;
            
        }
        
        //create Recipe
        $recipe = Vzaar\Recipe::create($params);
        
        echo PHP_EOL.'Create id: ' . $recipe->id  .' - '. $recipe->name;
        
        
        //lookup Recipe
        $lookup = Vzaar\Recipe::find($recipe->id);
        
        echo PHP_EOL.'Lookup id: ' . $lookup->id  .' - '. $lookup->name;
        
        //get all Recipes
        foreach(Vzaar\RecipesList::each_item() as $value) {
            
            echo PHP_EOL.'List Name : '. $value->name;
            
        }
        
        //modify Recipe
        echo PHP_EOL.'Lookup multipass: '. ($lookup->multipass ? 'true' : 'false');
        
        //change value
        $lookup->multipass = true;
        
        if($lookup->edited())
            $lookup->save();
        
        echo PHP_EOL.'Lookup multipass: '. ($lookup->multipass ? 'true' : 'false');
        
        //lookup
        $lookup = Vzaar\Recipe::find($lookup->id);
        
        echo PHP_EOL.'Lookup id: ' . $lookup->id  .' - '. $lookup->name;
        echo PHP_EOL.'Lookup multipass'. ($lookup->multipass ? 'true' : 'false');
        
        //get all Recipes
        foreach(Vzaar\RecipesList::each_item() as $value) {
            
            echo PHP_EOL.'List Name : '. $value->name;
            
        }
        
        //delete
        $lookup->delete();
        
        /**
         * Ingest Recipe List Example
         *
         * Iterate over recipes
         *
         */
        
        foreach(Vzaar\RecipesList::each_item() as $value) {
        
            echo PHP_EOL.'Name : '. $value->name;
        
        }
        
        echo PHP_EOL;
        
    }catch(Vzaar\VzaarException $ve){
        
        echo $ve->getMessage();
        
    }catch(Vzaar\VzaarError $verr){
        
        echo $verr->getMessage();
        
    }
?>
