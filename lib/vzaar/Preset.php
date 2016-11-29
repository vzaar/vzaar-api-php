<?php
    namespace VzaarApi;
    
    use VzaarApi\Record;
    use VzaarApi\Client;
    use VzaarApi\FunctionArgumentEx;
    
    class Preset extends Record {
        
        protected static $endpoint;
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
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
