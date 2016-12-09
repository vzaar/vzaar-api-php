<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\ArgumentTypeEx;
    use VzaarApi\Client;
    use VzaarApi\Video;

    class VideosList extends RecordsList {

        protected static $endpoint;
        protected static $recordClass;

        public function __construct($client = null) {
        
            self::$endpoint = '/videos';
            self::$recordClass = Video::class;
            
            parent::__construct($client);
            
        }
        
    }
    
?>
