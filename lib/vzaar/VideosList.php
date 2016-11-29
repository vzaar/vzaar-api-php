<?php
    namespace VzaarApi;
    
    use VzaarApi\RecordList;
    use VzaarApi\Video;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;

    class VideosList extends RecordList {

        protected static $endpoint;
        protected static $recordClass;

        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
        
            self::$endpoint = '/videos';
            self::$recordClass = Video::class;
            
            parent::__construct($client);
            
        }
        
    }
    
?>
