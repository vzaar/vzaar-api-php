<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;

    class Recipe extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
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
