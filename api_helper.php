<?php
require_once '../api_envs.php';
require_once '../Vzaar.php';

function generateRandomStr($len = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
};
    
?>