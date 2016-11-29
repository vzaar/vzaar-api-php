<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\Client;
    use VzaarApi\VzaarException;
    
    abstract class RecordList extends Record implements \Iterator, \Countable{
        
        protected $itemCursor;
        protected $pageCursor;
        
        protected function __construct(Client $client = null) {
        
            if(!isset(static::$recordClass))
                throw new VzaarError('Record type have to be configred');
            
            if(!class_exists(static::$recordClass))
                throw new VzaarError('Record type class is missing.');
            
            parent::__construct($client);
            
            $this->itemCursor = 0;
        }
        
        
        protected function updateRecord($data) {
            
            parent::updateRecord($data);
            
            //after the 'parent::updateRecord' is executed
            //the 'recordData' have dynamically defined
            //properties: 'data' and 'meta'
            
            foreach($this->recordData->data as $key => $value) {
                
                $obj = new static::$recordClass($this->httpClient);
                $obj->setRecord($value);
                
                $this->recordData->data[$key] = $obj;
            }
        }
        
        /**
         *
         *
         * abstract public int Countable::count ( void )
         *
         */
        public function count() {
            
            return \count($this->recordData->data);
        
        }
        
        /**
         *
         *
         * public user methods
         *
         */
        public function firstPage() {
        
            if(isset($this->first)) {
                
                $url = $this->first;
                $this->getPage($url);
                
                return true;
                
            } else
                return false;
            
        }
        
        public function nextPage() {
        
            if(isset($this->next)) {
                
                $url = $this->next;
                
                $this->getPage($url);
                
                return true;
                
            } else
                return false;
        }
        
        public function previousPage() {
        
            if(isset($this->previous)) {
                
                $url = $this->previous;
                $this->getPage($url);
                
                return true;
                
            } else
                return false;
        }
        
        public function lastPage() {
        
            if(isset($this->last)) {
                
                $url = $this->last;
                $this->getPage($url);
                
                return true;
                
            } else
                return false;
            
        }
        
        protected function getPage($url) {
            
            $params = \parse_url($url, PHP_URL_QUERY);
            
            $this->crudRead('?'.$params);
        
        }
        
        
        /**
         *
         * Class level methods
         *
         */
        public static function paginate($params = null, $client = null) {
            
            $list = new static($client);
            $list->crudRead(null,$params);
            
            return $list;
        }
    
        public static function each_item($params = null, $client = null) {
            
            $list = new static($client);
            $list->crudRead(null,$params);
            
            do{
                foreach($list as $key => $value) {
                    
                    yield $value;

                }
            }
            while($list->nextPage());
        }
        
        /**
         *
         * Array Iterator inteface implementation (for current page iteration)
         *
         */
        
        public function rewind() {
            
            $this->itemCursor = 0;
        }
        
        public function valid() {
        
            return isset($this->recordData->data[$this->itemCursor]);

        }
        
        public function current() {
        
            return $this->recordData->data[$this->itemCursor];

        }
        
        public function key() {
            
            return $this->itemCursor;

        }
        
        public function next() {
        
            ++$this->itemCursor;
        }

        
        /**
         *
         * Property Overloading
         *
         */
        
        public function __get($name) {
            
            $value = null;
            
            if(isset($this->recordData->meta->links->{$name}))
                $value = $this->recordData->meta->links->{$name};
            elseif(isset($this->recordData->meta->{$name}))
                $value = $this->recordData->meta->{$name};
        
            return $value;
            
        }
        public function __set($name, $value) {
            VzaarException::isReadonly();
        }
        public function __isset($name) {
            
            return (isset($this->recordData->meta->links->{$name}) |
                    isset($this->recordData->meta->{$name}));

        }
        public function __unset($name) {
        
            VzaarException::isReadonly();
        
        }
    
    }
?>
