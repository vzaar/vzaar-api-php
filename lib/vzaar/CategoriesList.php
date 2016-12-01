<?php
    namespace VzaarApi;

    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    use VzaarApi\Category;

    class CategoriesList extends RecordsList {

        protected static $endpoint;
        protected static $recordClass;
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/categories';
            self::$recordClass = Category::class;
            
            parent::__construct($client);
            
        }
        
        protected function findSubtree($id, $params = null) {
            
            $subtree = $id.'/subtree';
            
            $this->crudRead($subtree,$params);

        }
        
        public static function subtree($id, $params = null, $client = null){
            $list = new self($client);
            $list->findSubtree($id, $params);
            
            return $list;
        }
    
    }
?>
