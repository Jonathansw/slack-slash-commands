<?php
include('lib/forecast.io.php');

#Slack slash command to return the weather given a zipcode
# Uses forecast.io PHP API by https://github.com/tobias-redmann

$api_key = '0743673bd6f1c055452ab6ee2731dcfd'; //forecast.io API key
$units = 'us';  //units of measurement
$lang = 'en';  //language

#$command = $_POST['command'];   //Slack command name
$text = $_POST['text'];  //given zipcode

$forecast = new ForecastIO($api_key, $units, $lang);

#Convert zipcode to lat/long
$zip = $forecast->getLatLong($text);
$latitude= $zip['lat'];
$longitude = $zip['lng'];

#Int conversion on lat/long
$lat = (int) $latitude;
$long = (int) $longitude;

$geocode = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$text.'&sensor=true');
$output = json_decode($geocode);

$location = $output->results[0]->formatted_address;

$condition = $forecast->getCurrentConditions($lat, $long);
echo $location.'|'.$condition->getTemperature().'|'.$condition->getCloudCover(); //print info to slack
