<?php
    namespace Vzaar;
    
    use Vzaar\Record;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;
    
    class Preset extends Record {
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/encoding_presets';
            
            parent::__construct($client);
            
        }
        
        public static function find($params,$client = null) {
            
            $preset = new self($client);
            $preset->crudRead($params);
            
            return $preset;
        }

    }
?>
