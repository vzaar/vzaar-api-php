<?php
    namespace Vzaar;
    
    use Vzaar\Record;
    use Vzaar\FunctionArgumentEx;
    use Vzaar\CategoriesList;

    class Category extends Record {
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/categories';
            
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
