<?php
    namespace Vzaar;
    
    use Vzaar\RecordList;
    use Vzaar\Preset;
    use Vzaar\Client;
    use Vzaar\FunctionArgumentEx;
    
    class PresetsList extends RecordList {
        
        public function __construct($client = null) {
            
            FunctionArgumentEx::assertInstanceOf(Client::class, $client);
            
            //inherited static variable
            $this->endpoint = '/encoding_presets';

            //inherited static variable
            $this->recordClass = Preset::class;
            
            parent::__construct($client);
        }
        
    }
?>
