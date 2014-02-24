<?php
/*
 * @author Skitsanos
*/
	require_once '../Vzaar.php';

	Vzaar::$token = '1swM8e3KhNlbo4mP3uiiqSOXfRR3xVKW7RQSeUhSDhQ'; //
	Vzaar::$secret = 'skitsanos';

	$filename = '548.mov'; // the file must be located in the same directory as the script. If not use full disk path
	$file = getcwd() . '\\' . $filename;

	echo('file to upload: ' . $file);
	//$result=Vzaar::uploadVideo($file);
	$result = Vzaar::processVideo('vz4e2253c639fa46a998077b5db27a2d88', 'testing special characters like & so on', '', '', Profile::HighDefinition);

	echo($result);
?>