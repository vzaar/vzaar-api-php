<?php
/*
 * @author Skitsanos
*/
	require_once '../Vzaar.php';

	Vzaar::$token = 'token'; //
	Vzaar::$secret = 'secret';

	$filename = '548.mov'; // the file must be located in the same directory as the script. If not use full disk path
	$file = getcwd() . '\\' . $filename;

	echo('file to upload: ' . $file);
	//$result=Vzaar::uploadVideo($file);
	$result = Vzaar::processVideo('guid', 'testing special characters like & so on', '', '', Profile::HighDefinition);

	echo($result);
?>