<?php
    require_once '../Vzaar.php';
    Vzaar::$token = "GETUGkPFNC84JlzXkOMSYQFTOCAixOIiroh7oUj3k";
    Vzaar::$secret = "skitsanos";

    $req = Vzaar::setAuth('https://vzaar.com/api/test/whoami.json');

    header('Content-type: text/plain');
    echo($req->to_header());
?>
