<?php
    namespace VzaarApi\Exceptions;
    
    use VzaarApi\Exceptions\VzaarException;
    
    /*
     
     Thrown when record data not valid or corrupted
    
     */
    
    class RecordEx extends VzaarException {
        
        public static function isReadonly() {
            throw new self("The property is readonly");
        }
        
    }
?>
