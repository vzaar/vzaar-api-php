<?php
/* Vzaar API Implementation
 *
 * In order to use OAuth based API please make sure you've generated your own
 * token. All samples in /test folder provided just for the reference.
 *
 * This PHP implementation been tested under Linux/Apache and Windows/IIS.
 */

require_once 'Vzaar.php';
require_once 'User.php';
require_once 'Video.php';
require_once 'VideoList.php';
require_once 'AccountType.php';

//require_once 'HttpRequest.php';

/**
 * This API call returns the user's public details along with it's relevant metadata
 */
Vzaar::$token = 'token'; //
Vzaar::$secret = 'secret';

Vzaar::$enableHttpVerbose = false;

date_default_timezone_set("Europe/London");

/**
 * Public API
 */
//var_dump(Vzaar::getVideoDetails(17069));
//var_dump(Vzaar::getVideoDetails(36356, true));
//var_dump(Vzaar::getAccountDetails(1));
//var_dump(Vzaar::getVideoList('vzaar-user', true, 10));
//var_dump(Vzaar::searchVideoList('vzaar-user', 'true', 's3'));
//var_dump(Vzaar::getUserDetails('vzaar-user'));
//var_dump(Vzaar::getVideoDetails(21791,true));
//var_dump(Vzaar::getUploadSignature());

//print_r(Vzaar::getUploadSignature());

//print_r(Vzaar::uploadSubtitle('en', 4699267, 'subtitle'));

//print_r(Vzaar::getVideoDetails(632017, true));

/**
 * OAuth API
 */
//print_r(Vzaar::whoAmI());
//var_dump(Vzaar::searchVideoList('vzaar-user', true));
//print_r(Vzaar::getVideoList('vzaar-user', true, 2, 'vzaar-user'));
//print_r(Vzaar::getVideoDetails(4699267, true)->html);

//print_r(Vzaar::uploadVideo("/Users/vzaar-user/Documents/Vzaar/movie/sample.mkv"));
//print_r(Vzaar::processVideo("vz6a7c7ce3f65140fa8d0f1a4eae0d42a9", "Test video", "Some awesome video", "label"));
//print_r(Vzaar::getVideoDetails(4699267));
//print_r(Vzaar::editVideo(4699267,"Testing video", "For testing purpose", true ));
//print_r(Vzaar::getAccountDetails(1));
//print_r(Vzaar::generateThumbnail(4699267, 243));
//print_r(Vzaar::deleteVideo(4699267));
//print_r(ini_get_all());
//print_r(Vzaar::editVideo(434506, 'My Video tv Title', 'Some amazing description', 'true', 'http://example.com/746959'));
//print_r(Vzaar::deleteVideo(517885));
?>
