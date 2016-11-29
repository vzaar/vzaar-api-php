<?php
    require 'autoload.php';
    
    try {
        
        /**
         * Encoding Presets Example
         *
         * Presets List with preinitialized Client object
         *
         */
        
        $id = Vzaar\Client::$id;
        $token = Vzaar\Client::$token;
        
        //create configuration array for new API Client object
        $config = array('id' => $id,
                        'token' => $token);
        
        //create API Client
        $client = new Vzaar\Client($config);
        
        //get prestet ids array
        $presets = array();
        foreach(Vzaar\PresetsList::each_item(null,$client) as $preset) {
            
            $presets[] = $preset->id;
        
        }
        
        echo PHP_EOL.'Available presets: ' . json_encode($presets);
        
        /**
         * Encoding Presets Example
         *
         * Display Preset name with preinitialized Client object
         * - dependant on previous example
         *
         */
        
        if(!empty($presets)) {
            
            foreach($presets as $key => $value) {
            
                $start = $value;
                while($start > 0) {
                    $preset = Vzaar\Preset::find($value,$client);
                    --$start;
                }
                
                echo PHP_EOL.'Preset name: ' .$preset->name;
                
                echo PHP_EOL.'Rate Limit: ' .$preset->checkRateLimit();
                echo ' Rate Remaing: ' .$preset->checkRateRemaining();
                echo ' Rate Reset: ' .$preset->checkRateReset();
                
                echo PHP_EOL.'Client Rate Limit: ' .$client->checkRateLimit();
                echo ' Client Rate Remaing: ' .$client->checkRateRemaining();
                echo ' Client Rate Reset: ' .$client->checkRateReset();
                
                echo PHP_EOL;

            }
            
            echo PHP_EOL.'Client Rate Limit: ' .$client->checkRateLimit();
            echo ' Client Rate Remaing: ' .$client->checkRateRemaining();
            echo ' Client Rate Reset: ' .$client->checkRateReset();
            
            echo PHP_EOL;
        
        }
        
        echo PHP_EOL;
        
    }catch(Vzaar\VzaarException $ve){
        
        echo $ve->getMessage();
        
    }catch(Vzaar\VzaarError $verr){
        
        echo $verr->getMessage();
        
    }
?>
