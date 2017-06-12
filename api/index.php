<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/MvGalileoAPI.php';

$app = new Silex\Application();
$app['debug'] = true;

$galileo = new Galileo;

$app->get('/', function () {
	//
});

$app->get('/api', function () use ($galileo) {
	$url = $galileo->get_Production_Site() . "/JSON/Merchant/LowFareSearch.aspx?originCode=JKT&destinationCode=KUL&departDate=2016-11-23&returnDate=2016-22-26&routeType=2&totalAdult=1";
	//return ($url);
	
	return $galileo->get_Curl($url);
});

$app->run();
