<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;

    class Recipe extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/ingest_recipes';
            
            parent::__construct($client);
            
        }
        
        public function save($params = null) {
            
            $this->crudUpdate($params);

        }
        
        public function delete() {
            
            $this->crudDelete();

        }
        
        public static function find($params,$client = null) {
        
            $recipe = new self($client);
            $recipe->crudRead($params);
            
            return $recipe;
        }
        
        public static function create($params,$client = null) {
            $recipe = new self($client);
            $recipe->crudCreate($params);
            
            return $recipe;
        }
        
    }
?>
