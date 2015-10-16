<?php
require 'vendor/autoload.php';

use \GeoIp2\Database\Reader;
use \Forecast\Forecast;
use \AnthonyMartin\GeoLocation\GeoLocation;
use \JeroenDesloovere\Distance\Distance;

const API_ID_FORECAST = '5cc3a94fa51ff43574c767fa262747d1';

$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$reader = new Reader('GeoLite2-City.mmdb');
$record = $reader->city($ip);
$locate = $record->city->name ?: $record->country->name;
print( 'You in ' . $locate . ".<br>");

$forecast = new Forecast(API_ID_FORECAST);
$curentLocationLat = $record->location->latitude;
$curentLocationLong = $record->location->longitude;

$cityWeather = $forecast->get(
    $curentLocationLat,
    $curentLocationLong,
    null,
    [
        'units' => 'si',
        'exclude' => 'flags'
    ]
);
print('Temperature in your city ' . $cityWeather->currently->temperature . ' °С<br>');

$locations = ['Kiev', 'London', 'Moscow', 'Tokyo', 'New York', 'Ottawa', 'Beijing'];

foreach ($locations as $item) {
    $response = GeoLocation::getGeocodeFromGoogle($item);
    $latitude = $response->results[0]->geometry->location->lat;
    $longitude = $response->results[0]->geometry->location->lng;
    $distance = Distance::between(
        $latitude,
        $longitude,
        $curentLocationLat,
        $curentLocationLong
    );
    echo "Distance between $locate and $item = " . $distance . "km<br>";
}

