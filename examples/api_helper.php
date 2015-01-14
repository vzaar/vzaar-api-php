<?php
require_once './examples/api_envs.php';
require_once './src/Vzaar.php';

function generateRandomStr($len = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
};
    
?>