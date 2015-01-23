<?php
/*
 * @author Skitsanos
 */
require_once '../src/Vzaar.php';

Vzaar::$secret = 'secret'; 
Vzaar::$token = 'token';

if (isset($_GET['id'])) {
    $res = Vzaar::editVideo($_GET['id'], $_GET['title'], $_GET['description']);
    print_r($res);
}
?>
<form method='get'>
    Video Id: <input type='text' name='id' value='36213'/><br/>
    Title: <input type='text' name='title' value='New title'/><br/>
    Description: <input type='text' name='description' value='New description'/><br/>
    <input type='submit'/>
</form>