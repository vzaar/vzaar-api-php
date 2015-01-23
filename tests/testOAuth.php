<?php
    require_once '../src/Vzaar.php';
    Vzaar::$token = "token";
    Vzaar::$secret = "secret";

    $req = Vzaar::setAuth('https://vzaar.com/api/test/whoami.json');

    header('Content-type: text/plain');
    echo($req->to_header());
?>
