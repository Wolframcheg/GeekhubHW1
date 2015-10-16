<?php
require 'vendor/autoload.php';

use \Forecast\Forecast;
use \AnthonyMartin\GeoLocation\GeoLocation;
use \JeroenDesloovere\Distance\Distance;

class Main
{
    const API_ID_FORECAST = '5cc3a94fa51ff43574c767fa262747d1';

    private $forecast;
    private $currentLocation;

    public function __construct($ip)
    {
        $this->forecast = new Forecast(self::API_ID_FORECAST);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.telize.com/geoip/$ip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $this->currentLocation = json_decode($data);
    }

    public function printCurrentLocation()
    {
        echo 'You in ' . $this->currentLocation->city . '.<br>';
    }

    public function printWeather()
    {
        $cityWeather = $this->forecast->get(
            $this->currentLocation->latitude,
            $this->currentLocation->longitude,
            null,
            [
                'units' => 'si',
                'exclude' => 'flags'
            ]);
        echo 'Temperature in your city ' . $cityWeather->currently->temperature . ' °С<br>';
    }

    public function printDistances($locations)
    {
        foreach ($locations as $item) {
            $response = GeoLocation::getGeocodeFromGoogle($item);
            $latitude = $response->results[0]->geometry->location->lat;
            $longitude = $response->results[0]->geometry->location->lng;
            $distance = Distance::between(
                $latitude,
                $longitude,
                $this->currentLocation->latitude,
                $this->currentLocation->longitude
            );
            echo "Distance between {$this->currentLocation->city} and $item = " . $distance . "km<br>";
        }
    }

}