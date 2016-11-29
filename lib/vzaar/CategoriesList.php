<?php
    namespace Vzaar;

    use Vzaar\RecordList;
    use Vzaar\Category;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;

    class CategoriesList extends RecordList {
    
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/categories';
            
            //inherited static variable
            $this->recordClass = Category::class;
            
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
