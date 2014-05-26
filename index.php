<?php
/* Vzaar API Implementation
 * Works for public API and OAuth both
 * All methods listed till today (October 29, 2009) - fully supported
 *
 *
 * In order to use OAuth based API please make sure you've generated your own
 * token. All samples in /test folder provided just for the reference.
 *
 * This PHP implementation been tested under Linux/Apache and Windows/IIS both.
 *
 * @author skitsanos
 * @version 1.4
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

Vzaar::$enableHttpVerbose = true;

date_default_timezone_set("Europe/London");

/**
 * Public API
 */
//var_dump(Vzaar::getVideoDetails(17069));
//var_dump(Vzaar::getVideoDetails(36356, true));
//var_dump(Vzaar::getAccountDetails(1));
//var_dump(Vzaar::getVideoList('skitsanos', true, 10));
//var_dump(Vzaar::searchVideoList('skitsanos', 'true', 's3'));
//var_dump(Vzaar::getUserDetails('skitsanos'));
//var_dump(Vzaar::getVideoDetails(21791,true));
//var_dump(Vzaar::getUploadSignature());

//print_r(Vzaar::getUploadSignature('http://skitsanos.com'));

print_r(Vzaar::uploadSubtitle('en', 5579750, 'some subtitle'));

//print_r(Vzaar::getVideoDetails(632017, true));

/**
 * OAuth API
 */
//print_r(Vzaar::whoAmI());
//var_dump(Vzaar::searchVideoList('skitsanos', true));
//print_r(Vzaar::getVideoList('skitsanos', true, 2, 'skitsanos%20tv'));
//print_r(Vzaar::getVideoDetails(324763, true)->html);

//print_r(Vzaar::editVideo(434506, 'My Video tv Title', 'Some amazing description', 'true', 'http://skitsanos.tv/content/746959'));
//print_r(Vzaar::deleteVideo(517885));
?>