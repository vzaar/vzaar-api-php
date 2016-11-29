<?php
    namespace Vzaar;
    
    class VzaarException extends \Exception {
    }
    
    class FunctionArgumentEx extends VzaarException {
        
        public static function assertIsArray($params) {
            
            if(!is_null($params))
                if(!is_array($params))
                    throw new self("Parameter shoud be an array.");
            
        }
        
        public static function assertInstanceOf($class,$instance) {
            
            if(!is_null($instance))
                if(!($instance instanceof $class))
                    throw new self("Parameter should be instance of ".$class);
            
        }
        
        public static function isReadonly() {
            throw new self("The property is readonly");
        }
    
    }
    
    class RecordEx extends VzaarException {
    }
    
    class ArgumentValueEx extends FunctionArgumentEx {
    }
    
    class ConnectionEx extends VzaarException {
    }
    
    class ClientErrorEx extends ConnectionEx {
    }
    
    class S3uploadEx extends ConnectionEx {
    }

    class VzaarError extends \ErrorException {
    }
    

    
    

?>
