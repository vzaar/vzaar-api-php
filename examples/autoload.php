<?php
    require '../lib/vzaar.php';

    VzaarApi\Client::$client_id = $_ENV['VZAAR_CLIENT_ID'];
    VzaarApi\Client::$auth_token = $_ENV['VZAAR_AUTH_TOKEN'];

    //outputs equest/response details
    //VzaarApi\Client::$VERBOSE = true;

    // Update these values to match ones from your own account.
    $category_id = 3206;
    $encoding_preset_id = 3;
    $default_ingest_recipe = 67;
    $other_ingest_recipe = 73;

?>
