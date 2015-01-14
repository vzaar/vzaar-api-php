<?php
//package com.vzaar;

/**
 * Vzaar exception general use class.
 * @author Skitsanos
 */
interface IException {
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace

    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}


class VzaarException extends Exception implements IException {

    public function __construct($message = null, $code = 0) {
	if (!$message) {
	    throw new $this('Unknown '. get_class($this));
	}
	parent::__construct($message, $code);
    }

    public function __toString() {
	return "exception '".__CLASS__ ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine()."\nStack trace:\n".$this->getTraceAsString();
    }
}
