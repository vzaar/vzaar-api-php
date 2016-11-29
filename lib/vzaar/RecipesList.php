<?php
    namespace VzaarApi;
    
    use VzaarApi\RecordList;
    use VzaarApi\Recipe;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;
    
    class RecipesList extends RecordList {
        
        protected static $endpoint;
        protected static $recordClass;
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/ingest_recipes';
            self::$recordClass= Recipe::class;
            
            parent::__construct($client);
            
        }
        
    }
?>
