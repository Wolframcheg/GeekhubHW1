<?php
require 'Main.php';

$ip = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);

$main = new Main($ip);
$main->printCurrentLocation();
$main->printWeather();
$locations = ['Kiev', 'London', 'Moscow', 'Tokyo', 'New York', 'Ottawa', 'Beijing'];
$main->printDistances($locations);


