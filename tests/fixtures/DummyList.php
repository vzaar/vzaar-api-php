<?php
    namespace VzaarApi\Tests\Fixtures;
    
    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Tests\Fixtures\DummyRecord;
    
    class DummyList extends RecordsList {
        
        protected static $endpoint;
        protected static $recordClass;
        
        public static $list;
        
        public function __construct($client = null){
            
            self::$endpoint = '/dummy_endpoint';
            self::$recordClass = DummyRecord::class;
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
    
    DummyList::$list =<<<EOD
    {
        "data": [
        {
            "id": 44,
            "name": "Foo",
            "created_at": "2016-10-26T11:00:55.000Z",
            "updated_at": "2016-10-26T11:00:55.000Z"
        },
        {
            "id": 45,
            "name": "Bar",
            "created_at": "2016-10-26T11:00:55.000Z",
            "updated_at": "2016-10-26T11:00:55.000Z"
        }
        ],
        "meta": {
            "total_count": 2,
            "links": {
                "first": "https://api.vzaar.com/api/v2/dummy_endpoint?order=asc&page=1&state=ready",
                "last": "https://api.vzaar.com/api/v2/dummy_endpoint?order=asc&page=5&state=ready",
                "next": "https://api.vzaar.com/api/v2/dummy_endpoint?order=asc&page=4&state=ready",
                "previous": "https://api.vzaar.com/api/v2/dummy_endpoint?order=asc&page=2&state=ready"
            }
        }
    }
EOD;
    
?>
