<?php
/*
 * @author skitsanos
 */

require_once '../Vzaar.php';
require_once '../AccountType.php';

/**
 * This API call returns the details and rights for each vzaar account
 * type along with it's relevant metadata
 * @param <integer> $account is the vzaar account type. This is an integer.
 * @return <AccountType>
 */

$accountDetails = Vzaar::getAccountDetails(1);
var_dump($accountDetails);
?>
