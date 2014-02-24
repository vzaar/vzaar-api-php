<?php
/**
 * This example demonstrates how to extract video ID from the XML response 
 * returned by Vzaar API on Video Process call.
 * 
 * @author Skitsanos
 */
require('../Vzaar.php');

$arr = new XMLToArray('<vzaar-api><video><id>1234567</id></video></vzaar-api>');

print_r($arr->_data[0]["vzaar-api"]["video"]["id"]);
?>
