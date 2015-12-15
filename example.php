<?php

require_once 'vendor/autoload.php';

$api = new DatingVIP\API\Client;
$api->setUrl ('http://www.domain.com/api.json');
$api->setAuth ('username', 'p4$$w0rd');

$co_reg = new DatingVIP\API\CoReg ($api);
$result = $co_reg->getWebsiteGenders ();
print_r ($result->get ());

$result = $co_reg->getGeoFormats ('RS');
print_r ($result->get ());

$result = $co_reg->checkEmail ('example@domain.com');
print_r ($result->get ());

$result = $co_reg->checkUsername ('my_username');
print_r ($result->get ());

$result = $co_reg->zipAndCity ('US', '333');
print_r ($result->get ());

