<?php
    namespace Vzaar;
    
    use Vzaar\RecordList;
    use Vzaar\Recipe;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;
    
    class RecipesList extends RecordList {
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/ingest_recipes';
            
            //inherited static variable
            $this->recordClass= Recipe::class;
            
            parent::__construct($client);
            
        }
        
    }
?>
