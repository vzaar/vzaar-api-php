<?php
    namespace VzaarApi;
    
    use VzaarApi\Resources\Record;
    use VzaarApi\Exceptions\FunctionArgumentEx;
    use VzaarApi\Client;
    
    class Preset extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            self::$endpoint = '/encoding_presets';
            
            parent::__construct($client);
            
        }
        
        public static function find($params,$client = null) {
            
            $preset = new self($client);
            $preset->crudRead($params);
            
            return $preset;
        }

    }
?>
