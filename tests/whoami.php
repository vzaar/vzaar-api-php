<?php
	date_default_timezone_set("Europe/London");

	require_once '../Vzaar.php';

	if (isset($_GET['secret']) && isset($_GET['token'])) {
		Vzaar::$token = $_GET['token'];
		Vzaar::$secret = $_GET['secret'];

		echo('API reply: ' . Vzaar::whoAmI());
	} else {
		echo('Call whoAmI service as following: whoami.php?token=YOUR_API_TOKEN&secret=YOUR_VZAAR_USERNAME');
	}