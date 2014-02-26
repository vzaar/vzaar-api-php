<?php
/*
 * Vzaar getUserDetais test
 */

include_once '../Vzaar.php';

Vzaar::$token = 'PUT_HERE_TOKEN_UVE_GENERATED'; //
Vzaar::$secret = 'YOUR_USERNAME';

var_dump(Vzaar::getUserDetails('skitsanos'));
?>
