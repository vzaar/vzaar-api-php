<?php
/*
 * @author Skitsanos
 */
require_once '../Vzaar.php';

Vzaar::$token = 'GETUGkPFNC84JlzXkOMSYQFTOCAixOIiroh7oUj3k'; //
Vzaar::$secret = 'skitsanos';


$guid='';
if (isset($_GET['guid']))
    $guid=$_GET['guid'];

if (isset($_POST['guid'])) {
    $apireply = Vzaar::processVideo($_POST['guid'], $_POST['title'], $_POST['description'], 1);    
}

?>
<!DOCTYPE html>
<html>
    <head>
	<title>Process video</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Skitsanos.com"/>
    </head>

    <body>
<?php
        print_r('Returned Video ID: '.$apireply);
?>
	<form action="uploadvideoprocess.php" method="post">
	    <table cellspacing='0' cellpadding='0' border='0'>
		<tr>
		    <td>GUID:</td>
		    <td><?php echo $guid; ?></td>
		</tr>
		<tr>
		    <td>Title:</td>
		    <td><input type='text' size='100' name='title'/></td>
		</tr>
		<tr>
		    <td>Description:</td>
		    <td><input type='text' size='100' name='description'/></td>
		</tr>
	    </table>
	    <input type='hidden' name='guid' value='<?php echo $guid; ?>'/>
	    <input type="submit" value="Process video">
	</form>
    </body>
</html>