<?php
    namespace VzaarApi;
    
    use VzaarApi\RecordList;
    use VzaarApi\Preset;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;
    
    class PresetsList extends RecordList {
        
        protected static $endpoint;
        protected static $recordClass;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            self::$endpoint = '/encoding_presets';
            self::$recordClass = Preset::class;
            
            parent::__construct($client);
        }
        
    }
?>
