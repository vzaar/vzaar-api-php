<?php
/*
 * @author skitsanos
 */

require_once '../Vzaar.php';


Vzaar::$secret = 'YOUR_VZAAR_USERNAME';
Vzaar::$token = 'API_TOKEN';

if (isset($_GET['id']))
{
    $res=Vzaar::deleteVideo($_GET['id']);
    print_r($res);
}
?>
<form method='get'>
Video Id: <input type='text' name='id' value='0'/>
<input type='submit'/>
</form>
