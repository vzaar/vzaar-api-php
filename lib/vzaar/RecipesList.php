<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    use VzaarApi\Recipe;
    
    class RecipesList extends RecordsList {
        
        protected static $endpoint;
        protected static $recordClass;
    
        public function __construct($client = null) {
            
            self::$endpoint = '/ingest_recipes';
            self::$recordClass= Recipe::class;
            
            parent::__construct($client);
            
        }
        
    }
?>
