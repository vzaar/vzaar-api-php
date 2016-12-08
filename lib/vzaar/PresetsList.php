<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\RecordsList;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    use VzaarApi\Preset;
    
    class PresetsList extends RecordsList {
        
        protected static $endpoint;
        protected static $recordClass;
        
        public function __construct($client = null) {
            
            self::$endpoint = '/encoding_presets';
            self::$recordClass = Preset::class;
            
            parent::__construct($client);
        }
        
    }
?>
