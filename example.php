<?php
require_once dirname(__FILE__) . '/lifx-http.php';

// You can get an authentication token at https://cloud.lifx.com/settings
$lifx = new LIFX_Http("you token here");

$lights = $lifx->getLights();

print_r($lights);

// Toggle your first listed bulb
#$lifx->toggleLights("id:".$lights[0]["id"]);

// Power on or off your bulb
/*
if($lights[0]["power"] == "on"){
    $lifx->powerLights("off",1,"id:".$lights[0]["id"]);
} else {
    $lifx->powerLights("on",1,"id:".$lights[0]["id"]);
}
 */

// Set the color of all bulbs to blue
#$lifx->setColor("blue");