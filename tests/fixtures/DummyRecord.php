<?php
    namespace VzaarApi\Tests\Fixtures;
    
    use VzaarApi\Resources\Record;
    
    class DummyRecord extends Record {
        
        protected static $endpoint;
        
        public static $lookup;
        
        public function __construct($client = null){
            
            self::$endpoint = '/dummy_endpoint';
            parent::__construct($client);
        }
        
        public function create($params = null) {
            
            $this->crudCreate($params);
            
        }
        
        public function read($path = null, $params = null) {
            
            $this->crudRead($path,$params);
            
        }
        
        public function update($params = null) {
            
            $this->crudUpdate($params);
            
        }
        
        public function delete() {
            
            $this->crudDelete();
            
        }
    }
    
    DummyRecord::$lookup =<<<EOD
    {
        "data": {
            "id": 44,
            "name": "Foo",
            "created_at": "2016-10-26T11:00:55.000Z",
            "updated_at": "2016-10-26T11:00:55.000Z"
        }
    }
EOD;
    
?>
