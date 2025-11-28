<?php

$config = [
    'host' =>  '',
    'username' =>  '',
    'password' =>  '',
    'port' => (int)( 465),
    'from' =>  '',
    'encryption' =>  'ssl',
    'require_https' => filter_var( 'true', FILTER_VALIDATE_BOOLEAN),
];
