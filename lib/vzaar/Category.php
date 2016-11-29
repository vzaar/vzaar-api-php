<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\FunctionArgumentEx;
    use VzaarApi\CategoriesList;

    class Category extends Record {
        
        protected static $endpoint;
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/categories';
            
            parent::__construct($client);

        }
        
        public function subtree($params = null) {

            $this->assertRecordValid();
        
            return CategoriesList::subtree($this->id, $params);

        }
        
        public static function find($params, $client = null) {
            $category = new self($client);
            $category->crudRead($params);
            
            return $category;
        }
    }
?>
